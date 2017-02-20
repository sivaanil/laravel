<?php

namespace Unified\Services;

use Illuminate\Database\DatabaseManager;
use PDOException;
use DB;
use Log;
use Unified\Browser\BrowserManager;
use Unified\Models\NetworkTree;
use Unified\Models\DeviceProp;

/**
 * This is a utility intended to be run on a SiteGate to "scatter" the data_updated values
 * in the css_networking_device_prop table. This is to avoid too many props with the date_updated in the same second which
 * can cause the scanners to get stuck repeatedly downloading the props for on one second.
 *
 * NOTE: This service is a temporary solution to the problem of data_updated values. Once a permanent solution is in place,
 * this service should be removed.
 * @TODO: See NOTE
 * @author Anthony Levensalor <anthony.levensalor@csquaredsystems.com>
 */
class FixPropTimes {
    /**
     * @var DatabaseManager
     */
    private $db;

    const MAX_TS_COUNT = 1000;
    const MAX_UPDATE   = 500;

    /**
     * Fixes the property times to be within the acceptable functional
     * limits.
     */
    public function Process() {

        $changeset = $this->getRecordsToFix();
        $fixed = $this->fixTimes($changeset);
        Log::info(__CLASS__." fixed $fixed property times");
        return $fixed;
    }

    /**
     * Does the work of fixing the prop times for all passed records
     *
     * @param Array<StdClass> $changeset
     *
     * @return number<integer> Number of altered records from this operation.
     */
    protected function fixTimes(Array $changeset) {
        $fixCount = 0;

        foreach ($changeset as $row) {
            $date = $row->date_updated;
            $propCount = $row->prop_count;

            while ($propCount >= self::MAX_TS_COUNT) {
                $devices = $this->getDevicesUpdatedOnDate($date);
                $fixCount += $this->updateDevices($devices);
                $propCount = $this->getPropCountForDate($date);
                // Pause for 1 second to let time pass
                sleep(1);
            }
        }
        return $fixCount;
    }

    /**
     * Retrieves the number of properties with the passed in date.
     * @param String $date
     *
     * @return number
     */
    protected function getPropCountForDate($date) {
        return NetworkTree::getPropCountForDate($date);
    }


    /**
     * Fetch all devices updates on the given date
     *
     * @param String $date
     *
     * @return Array<StdClass> device_id records to update
     */
    protected function getDevicesUpdatedOnDate($date) {
        return NetworkTree::getDevicesUpdatedOn($date);
    }


    /**
     * Touch the records to set the date_updated field with the current timestamp.
     *
     * @param Array<StdClass> $devices
     *
     * @return number Number of device rows affected by the update
     */
    protected function updateDevices(Array $devices) {
        $deviceIDs = [];
        foreach ($devices as $device) {
            $deviceIDs[] = $device->device_id;
        }
        return DeviceProp::UpdateDevices($deviceIDs);
    }

    /**
     * Retrieves all the records that have more than MAC_TS_COUNT
     * timestamp entries.
     *
     * Returned Records:
     *   $out->date_updated The timestamp being highlighted for fix.
     *   $out->prop_count   The number of properties that share the timestamp
     *
     * @return Array<StdClass> Array of records fitting the format noted above.
     */
    protected function getRecordsToFix() {
        return NetworkTree::getRecordsToFix();

    }
}

