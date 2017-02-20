<?php

namespace Unified\Browser;

use Carbon\Carbon;
use DB;
use Illuminate\Database\QueryException;
use stdClass;
use Unified\Devices\DeviceInfo;
use Unified\Models\BrowserSlot;
use Unified\Models\Device;
use Unified\Models\DeviceAlarm;
use Unified\Models\Scan;
use Unified\Models\GeneralConfig;
use Unified\Http\Helpers\GuacamoleHelper;

/**
 * Description of BrowserManager
 *
 * @author ross.keatinge
 */
class BrowserManager
{

    const MAX_SESSIONS = 6;
    const SESSION_TIMEOUT_MINS = 10;
    const SLOT_STATUS_INACTIVE = 0;
    const SLOT_STATUS_ACTIVE = 1;
    const SLOT_STATUS_DELETING = 2;
    // All SiteGates can have this password.
    // This is safe so long as the VNC port is blocked from the outside world.
    const VNC_PWD = 'V3KNx#4t';
    const GUAC_ADMIN_USER_ID = 1;

    /**
     * Find or create a browser slot for the session
     * Navigate the browser in that session the requested node.
     * Return the URL of the Guacamole session
     *
     * @param integer $nodeId
     * @param string $sessionId
     *   Either the Lavavel session id for local users on SiteGate
     *   or the SitePortal session id supplied via the API for SitePortal users.
     * @return string|null
     *   URL if successful, null if there were no available slots
     */
    public function GetGuacUrlForNode($nodeId, $sessionId = null)
    {
        if (empty($sessionId)) {
            // No session id supplied so use Laravel session for local user
            $sessionId = session()->getId();
        }

        // Get slot for this session
        $slot = $this->GetSlot($sessionId);

        if ($slot === null) {
            // No slot available
            return 'Error: All Available Remote Browser Sessions are currently in use';
        }

        // Get the URL of the device
        $deviceInfo = new DeviceInfo();
        $url = $deviceInfo->getWebUrl($nodeId);

        if ($url === null) {
            $url = 'about:blank';
        }

        // Send a command to the TCP server to tell the browser to go to this URL
        $this->SendCmd("url {$slot->id} $url");

        list($guacUserName, $guacPassword) = $this->CreateUserAndConnection($slot->id);

        return route('LaunchBrowser', [
            'guacUserName' => $guacUserName,
            'guacPassword' => $guacPassword,
            'connectionId' => $slot->connection_id
        ]);
    }

    /**
     * Send a command to the TCP server controlling the browser.
     * The TCP server is running via Supervisor as c2-guest.
     *
     * @param string $cmd
     */
    public function SendCmd($cmd)
    {
        // this talks to browser-listener.php running as a tcp server
        // TODO, TECHDEBT - We're hiding any error raised here, we should be handling them without the @ sign - ~A! (Sprint 1)
        $client = @stream_socket_client('tcp://127.0.0.1:6000');

        // it should always be running. Commands are terminated with a linefeed.
        if ($client !== false) {
            fwrite($client, "$cmd\n");
            fclose($client);
        }
    }

    /**
     * Get a slot for this session
     *
     * @param type $sessionId
     * @return stdClass
     */
    private function GetSlot($sessionId)
    {
        $slotsAvailable = true;
        $slot = null;

        // loop around:
        // Get candidate slot, try to update, continue until successful or no slots are available

        while ($slot === null && $slotsAvailable) {
            $candidate = $this->GetCandidateSlot($sessionId);

            if ($candidate === null) {
                $slotsAvailable = false;    // no available slots
            } else {
                // claim the candidate slot by saving it with updated info
                $candidate->session_id = $sessionId;

                // Generate a unique connection_id for this session and slot
                // This is used as the Guacamole session id and for our Javascript ping
                // Make it a URL safe base64
                $hash = sha1($candidate->id . $sessionId, true);
                $candidate->connection_id = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');

                $candidate->last_activity_at = time();
                $candidate->status = self::SLOT_STATUS_ACTIVE;

                if ($this->SaveSlot($candidate)) {
                    // successfully claimed
                    $slot = $candidate;
                }
            }
        }

        return $slot;
    }

    /**
     * Save a slot with updated info
     *
     * @param BrowserSlot $slot
     * @return boolean
     *   true on success
     */
    private function SaveSlot(stdClass $slot) {
        // TODO - Pretty sure we can just use BrowserSlot objects here, but this is not that pass at the code. ~A! (Sprint 1)
        return BrowserSlot::saveSlot($slot);
/*        $success = false;

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
*/
    }

    /**
     * Looks for an available slot.
     * It's only a "candidate" until we successfully claim it by saving
     *
     * @param type $sessionId
     *   the Laraval session
     * @param type $reuse
     *   false will alow multiple slots per web session
     * @return stdClass|null
     *   candidate slot or null if none available
     */
    private function GetCandidateSlot($sessionId, $reuse = true) {
        return BrowserSlot::getCandidateSlot($sessionId, $reuse = true);
/*        // get all valid slots
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
*/
    }

    /**
     * Reset to inactive browser slots that have had no activity for the timeout period
     * Called from an Artisan command
     *
     * @return array
     *   A list of ids that were reset
     */
    public function GarbageCollect() {

        return GuacamoleHelper::garbageCollect($this);
/*
        // Delete old connections and users from the Guacamole tables.
        // Each user has one related connection
        // Rows in related tables are also deleted because the foreign keys are set to cascade deletes.
        $sql = 'DELETE FROM guacamole_connection
            WHERE connection_id IN
            ( SELECT gcp.connection_id FROM guacamole_user gu
            INNER JOIN guacamole_connection_permission gcp ON gcp.user_id = gu.user_id
            WHERE gu.user_id != :adminUserId
            AND gu.valid_until < :today )';

        $today = Carbon::today();

        DB::statement($sql, [
            'today' => $today,
            'adminUserId' => self::GUAC_ADMIN_USER_ID
        ]);

        DB::table('guacamole_user')
                ->where('user_id', '!=', self::GUAC_ADMIN_USER_ID)
                ->where('valid_until', '<', $today)
                ->delete();

        // Expire slots that have not been used within the timeout

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
*/
    }

    /**
     * Ping the connection to tell us that it is still alive.
     * Some Javascript in Guacamole client.xhtml does this.
     *
     * @param type $connectionId
     * @return integer
     *   Number of connections updated. Should be 1 or 0.
     */
    public function PingConnection($connectionId)
    {
        $slot = BrowserSlot::where([
                'connection_id' => $connectionId,
                'status'        => BrowserSlot::ACTIVE,
            ])->first();
        return $slot->ping();


//        $updatedCount = DB::table('browser_slots')
//                ->where('connection_id', '=', $connectionId)
//                ->where('status', '=', self::SLOT_STATUS_ACTIVE)
//                ->update(['last_activity_at' => time()]);

//        return $updatedCount;
    }

    public function ExpireSlots($sessionId = null)
    {

        $slots = BrowserSlot::getActiveSlotsForsession($sessionId);
        $slots->each(function($item, $key) {
            $item->expire();
        });

//        $timedOut = time() - (self::SESSION_TIMEOUT_MINS * 60) - 60;

//        DB::table('browser_slots')
//                ->where('session_id', '=', $sessionId)
//                ->where('status', '=', self::SLOT_STATUS_ACTIVE)
//                ->update(['last_activity_at' => $timedOut]);
    }

    public function DeactivateAllSlots()
    {
        BrowserSlot::deactivateAll();
//        DB::table('browser_slots')
//                ->where('status', '!=', self::SLOT_STATUS_INACTIVE)
//                ->update(['status' => self::SLOT_STATUS_INACTIVE]);
    }


    private function CreateUserAndConnection($slotId)
    {
        $guac = new GuacamoleHelper();
        return $guac->createUserAndConnection($slotId);
/*
        $userName = str_random(16) . '-' . time();
        $password = str_random(30);

        $result = exec('whoami');


        // The Guacamole user table has a valid_until date (no time) and a access_window_end (no date)
        // This seems to only affect the ability to login, not continued use after login so it is useful
        // to protect against someone stealing or bookmarking the initial /browse/{username}/{password} URL
        // Since these two columns are independent, it gets a little messy close to midnight.
        // Take one snapshot of the datetime now.
        $now = Carbon::now();

        // calculate 2 minutes from now which is plenty of time to login.
        // Note that Carbon is mutable, hence the ->copy()
        $windowEnd = $now->copy()->addMinutes(5);

        // If the window ends tomorrow, make it today at 23:59:59
        if ($windowEnd >= $now->tomorrow()) {
            $windowEnd = $windowEnd->setTime(23, 59, 59);
        }

        $userId = DB::table('guacamole_user')
                ->insertGetId([
            'username' => $userName,
            'password_hash' => hash('sha256', $password, true),
            'valid_until' => $now->today(),
            'access_window_end' => $windowEnd,
            'timezone' => env('TIMEZONE')
        ]);

        $connectionId = DB::table('guacamole_connection')
                ->insertGetId([
            'connection_name' => $userName,
            'protocol' => 'vnc',
            'max_connections' => 0,
            'max_connections_per_user' => 0,
        ]);

        $params = [
            'hostname' => 'localhost',
            'password' => self::VNC_PWD,
            'port' => 5900 + $slotId,
            'enable-sftp' => 'true',
//            'sftp-username' => 'c2-guest',
//            'sftp-password' => 'e4MNW7Gl,7=u&13943;Q',
            'sftp-username' => 'guest',
            'sftp-password' => 'e4MNW7Gl,7=u&13943;Q',
            'sftp-hostname' => 'localhost',
            'sftp-directory' => './Downloads',
//            'swap-red-blue' => 'true',
        ];

        $insertData = [];

        foreach ($params as $key => $value) {
            $insertData[] = [
                'connection_id' => $connectionId,
                'parameter_name' => $key,
                'parameter_value' => $value,

            ];
        }

        DB::table('guacamole_connection_parameter')
                ->insert($insertData);

        DB::table('guacamole_connection_permission')
                ->insert([
                    'user_id' => $userId,
                    'connection_id' => $connectionId,
                    'permission' => 'READ'
                 ]);

        return [$userName, $password];
*/
    }

    /**
     * Resets process flags for running scans
     * TODO: move this from BrowserManager to somewhere logical
     *
     */
    public function ResetProcessFlags() {
        GeneralConfig::resetProcessFlags();
        Device::clearScanningFlags();
        Scan::clearScanningFlags();

        //SITEGATE ONLY!!! Insert network_tree_log entry to show reboot detected
        $alarm = new DeviceAlarm;
        $alarm->severity_id = 4;
        $alarm->device_id = 1;
        $alarm->description = 'System POWER-UP detected';
        $alarm->raised = DB::raw('now()');
        $alarm->cleared = DB::raw('now()');
        $alarm->is_offline = 0;
        $alarm->ignored = 0;
        $alarm->date_updated = DB::raw('now()');
        $alarm->prop_alarm = 0;
        $alarm->duration_exempt = 0;
        $alarm->can_acknowledge = 0;
        $alarm->is_trap = 0;
        $alarm->is_heartbeat = 0;
        $alarm->is_threshold = 0;
        $alarm->has_notes = 0;
        // $alarm->cleared_order = '2654412971';
        $alarm->log_date_time = '0';
        $alarm->is_chronic = 0;
        $alarm->acknowledged = 0;
        $alarm->cleared_bit = 1;
        $alarm->is_perimeter = 0;
        $alarm->permit_notifications = 1;
        $alarm->save();

    }


}
