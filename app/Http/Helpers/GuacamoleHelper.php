<?php

namespace Unified\Http\Helpers;

use Carbon\Carbon;
use Unified\Models\GuacamoleUser;
use Unified\Models\BrowserSlot;
use Unified\Models\GuacamoleConnection;
use Unified\Models\GuacamoleConnectionParameter;
use Unified\Models\GuacamoleConnectionPermission;
use DB;

class GuacamoleHelper {

    const MAX_SESSIONS = 6;
    const SESSION_TIMEOUT_MINS = 10;
    const SLOT_STATUS_INACTIVE = 0;
    const SLOT_STATUS_ACTIVE = 1;
    const SLOT_STATUS_DELETING = 2;
    // All SiteGates can have this password.
    // This is safe so long as the VNC port is blocked from the outside world.
    const VNC_PWD = 'V3KNx#4t';
    const GUAC_ADMIN_USER_ID = 1;


    public static function garbageCollect($browserManager) {

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
            if (BrowserSlot::SaveSlot($slot)) {
                $browserManager->SendCmd('killvnc ' . $slot->id);
                $slot->status = self::SLOT_STATUS_INACTIVE;
                BrowserSlot::SaveSlot($slot);
                $resetIds[] = $slot->id;
            }
        }

        return $resetIds;

    }

    public function createUserAndConnection($slotId) {
        // Username and password are random based on timestamp
        $username = str_random(16) . '-' . time();
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

        $userData = [
            'username'          => $username,
            'password_hash'     => hash('sha256', $password, true),
            'valid_until'       => $now->today(),
            'access_window_end' => $windowEnd,
            'timezone'          => env('TIMEZONE'),
        ];

        $user = new GuacamoleUser($userData);
        $user->save();

        $connData = [
            'connection_name'           => $username,
            'protocol'                  => 'vnc',
            'max_connections'           => 0,
            'max_connections_per_user'  => 0,
        ];

        $connection = new GuacamoleConnection($connData);
        $connection->save();

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
                'connection_id' => $connection->id,
                'parameter_name' => $key,
                'parameter_value' => $value,

            ];
        }
        GuacamoleConnectionParameter::insert($insertData);


        $perm = new GuacamoleConnectionPermission([
            'user_id'       => $user->id,
            'connection_id' => $connection->id,
            'permission'    => 'READ',
        ]);
        $perm->save();

        return [$username, $password];
    }
}

