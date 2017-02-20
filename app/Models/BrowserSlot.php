<?php

namespace Unified\Models;

use Eloquent;
use DB;
use stdClass;

class BrowserSlot extends Eloquent {

    const INACTIVE = 0;
    const ACTIVE   = 1;
    const DELETING = 2;

    // TODO - this should be in config or .env
    const SESSION_TIMEOUT_MINS = 10;
    const SLOT_STATUS_INACTIVE = 0;
    const SLOT_STATUS_ACTIVE = 1;
    const SLOT_STATUS_DELETING = 2;
    const MAX_SESSIONS = 6;

    protected $table = "browser_slots";

    protected $fillable = ['session_id','connection_id','last_activity_at','status','row_version'];

    public $timestamps = false;

    public static function deactivateAll() {
        self::where('status', '!=', self::ACTIVE)->update(['status' => self::INACTIVE]);
    }

    public static function saveSlot(StdClass $slot) {
        $success = false;

        // row_version provides optimistic locking to avoid two users grabbing the same slot
        // https://en.wikipedia.org/wiki/Optimistic_concurrency_control

        $params = [
            'session_id' => $slot->session_id,
            'connection_id' => $slot->connection_id,
            'last_activity_at' => $slot->last_activity_at,
            'status' => $slot->status,
            'row_version' => $slot->row_version + 1
        ];

        // Using a query builder here rather than Eloquent save because I don't quite understand or trust
        // how to save an Eloquent object with extra WHERE conditions.

        if (!isset($slot->isNew)) {
            // Already in database. Save using optimistic locking

            $updatedCount = DB::table('browser_slots')
                    ->where('id', '=', $slot->id)
                    ->where('row_version', '=', $slot->row_version)
                    ->update($params);

            // If another user updated or deleted this slot before us then row_version will have changed
            // and therefore $updatedCount will not be 1.

            $success = $updatedCount === 1;

            if ($success) {
                $slot->row_version++;
            }
        } else {

            // Not in the database so we need to insert and trap any duplicate key exception
            $params['id'] = $slot->id;

            try {
                DB::table('browser_slots')
                        ->insert($params);
                $success = true;
            } catch (QueryException $ex) {
                // 23000 is MySQL's code for duplicate key violation
                if ($ex->getCode() != 23000) {
                    throw $ex;
                }
            }
        }

        return $success;
    }

   /**
     * Looks for an available slot.
     * It's only a "candidate" until we successfully claim it by saving
     *
     * @param String $sessionId
     *   the Laraval session
     * @param Boolean $reuse
     *   false will alow multiple slots per web session
     * @return Mixed
     *   candidate slot or null if none available
     */
    public static function getCandidateSlot($sessionId, $reuse = true) {
        // get all valid slots
        $allSlots = DB::table('browser_slots')
                ->select('id', 'session_id', 'connection_id', 'status', 'row_version')
                ->whereBetween('id', [1, self::MAX_SESSIONS])
                ->get();

        $oursToReuse = false;
        $otherToClaim = false;

        // remembers the ids to use later if we need to create a new slot
        $ids = [];

        foreach ($allSlots as $slot) {
            $ids[$slot->id] = true;

            if ($reuse && $slot->session_id === $sessionId && $slot->status != self::SLOT_STATUS_DELETING) {
                // we are reusing and this is our own slot
                // break because this really is the one we want
                $oursToReuse = $slot;
                break;
            }

            if ($slot->status == self::SLOT_STATUS_INACTIVE) {
                // we could use this one.
                // Only break if we don't want a possible $oursToReuse
                $otherToClaim = $slot;
                if (!$reuse) {
                    break;
                }
            }
        }

        if ($oursToReuse !== false) {
            return $oursToReuse;    // reusing our own slot
        }

        if ($otherToClaim !== false) {
            return $otherToClaim;   // reusing an inactive slot
        }

        if (count($allSlots) === self::MAX_SESSIONS) {
            return null;    // no slots available
        }

        // We still have room. Create a new slot
        $newIdToCreate = false;

        // find the first $id that doesn't exist
        for ($id = 1; $id <= self::MAX_SESSIONS; $id++) {
            if (!isset($ids[$id])) {
                $newIdToCreate = $id;
                break;
            }
        }

        $newSlot = new stdClass();
        $newSlot->id = $newIdToCreate;
        $newSlot->row_version = 0;
        $newSlot->isNew = true;

        return $newSlot;

    }

    /**
     * Expire slots that have not been used within the timeout parameter.
     *
     * @return Array<number> Id's of the now-expired slots
     */
    public static function expireUnused() {
        $oldest = time() - (self::SESSION_TIMEOUT_MINS * 60);
        $inactiveSlots = DB::table('browser_slots')
                ->select('id', 'session_id', 'connection_id', 'last_activity_at', 'row_version')
                ->whereBetween('id', [1, self::MAX_SESSIONS])
                ->where('status', '=', self::SLOT_STATUS_ACTIVE)
                ->where('last_activity_at', '<', $oldest)
                ->get();

        $resetIds = [];

        foreach ($inactiveSlots as $slot) {
            $slot->status = self::SLOT_STATUS_DELETING;

            // the optimistic locking will save us if some activity happens an instant before we save
            if ($this->SaveSlot($slot)) {
                $this->SendCmd('killvnc ' . $slot->id);
                $slot->status = self::SLOT_STATUS_INACTIVE;
                $this->SaveSlot($slot);
                $resetIds[] = $slot->id;
            }
        }
        return $resetIds;
    }

    public static function getActiveSlotsForsession($sessionId) {
        return self::where([
           'session_id' => $sessionId,
           'status'     => self::ACTIVE,
        ])->get();
    }


    public function expire() {

        $timeout = time() - (self::SESSION_TIMEOUT_MINS * 60) - 60;
        return $this->update(['last_activity_at' => $timeout]);
    }

    public function ping() {
       return $this->update(['last_activity_at' => time()]);
    }

    public function expireSlots($sessionId = null) {}


}
