<?php

namespace Unified\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Unified\Models\Device;
use Unified\Models\DeviceAlarm;

class ScanSelf extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:scan-self';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Self for issues.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function raiseRaidAlarm()
    {
        // Insert Alarm to indicate RAID failure
        $alarm = new DeviceAlarm;
        $alarm->severity_id = 1; //critical
        $alarm->device_id = 1;
        $alarm->description = 'RAID Failure Detected';
        $alarm->raised = DB::raw('now()');
        $alarm->cleared = null;
        $alarm->cleared_bit = 0;
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
        $alarm->log_date_time = '0';
        $alarm->is_chronic = 0;
        $alarm->acknowledged = 0;
        $alarm->is_perimeter = 0;
        $alarm->permit_notifications = 1;
        $alarm->save();

        return(0);
    }

// raiseRaidAlarm

    private function checkRaidStatus()
    {
        $result = array();
        $returnValue = 0;

        exec('cat /proc/mdstat', $result, $returnValue);

        // ***** DEBUGGING AND INFORMATION ***** 
        // You can read a file containing RAID device data, for testing, without an actual RAID error. 
        //
	  // Example File Data, identical to data returned from '/proc/mdstat'.
        // *************************************************************************************
        // Personalities : [linear] [multipath] [raid0] [raid1] [raid6] [raid5] [raid4] [raid10]
        // md1 : active raid1 sdb3[2]
        //       9757568 blocks super 1.2 [2/1] [_U]
        //
	  // md0 : active raid1 sdb2[2]
        //       107383680 blocks super 1.2 [2/2] [UU]
        //
	  // unused devices: <none>
        // *************************************************************************************
        //
	  // This code reads a file containing the same data to be checked, for testing.
        // *************************** 
        // $hndl = fopen("/tmp/Result.txt", "r"); 
        // $textRead = fread($hndl, filesize("/tmp/Result.txt")); 
        // fclose($hndl); 
        // $result = explode("\n", $textRead); 
        // *************************** 
        // Look for the pattern indicating the [number of component devices / and the number available].
        // If they don't match, then some of the devices are down.
        // [2/1] -> Two devices; One working.
        // [_U]  -> First one down; Second one up.

        for ($x = 0; $x <= max(array_keys($result)) && $returnValue === 0; $x++) {
            $matches = array();
            if (preg_match("/.*\[([0-9]+)\/([0-9]+)\]/", $result[$x], $matches)) {
                // Match
                if ($matches[1] != $matches[2]) {
                    $returnValue = 1;
                }
            }
        }

        // If an error is found, check to see that an uncleared error hasn't already been generated.
        if ($returnValue == 1) {

            $alarmCount = DB::table("css_networking_device_alarm")
                    ->select(DB::raw("count(*) as cnt"))
                    ->where('device_id', '=', 1)
                    ->where('description', '=', "RAID Failure Detected")
                    ->where("cleared_bit", "=", 0);

            if ($alarmCount->get()[0]->cnt > 0) {
                $returnValue = 0;
            }
        }

        return $returnValue;
    }

// checkRaidStatus

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $returnValue = self::checkRaidStatus();
        if ($returnValue != 0) {
            self::raiseRaidAlarm();
        }

        return $returnValue;
    }

}

// ScanSelf
