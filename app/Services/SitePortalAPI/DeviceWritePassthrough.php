<?php

namespace Unified\Services\SitePortalAPI;

use DB;
use Log;

class DeviceWritePassthrough
{

    public function handle($newPropertySettings, $nodeID, $userID)
    {

        require_once ENV('CSWAPI_ENV') . '/networking/class/DeviceService.php';
Log::debug(__METHOD__ . "::WRITING A DEVICE!!!!", (array) $newPropertySettings);
        global $dbConn;
        //here we're mapping the SitePortal's prop_id to the SiteGate's prop_id
        for($i = 0; $i < count($newPropertySettings); $i++) {
            Log::debug(__METHOD__ . "::123!", (array) $nodeID);
            // TODO - Skipping this in UN-80 until I understand the mechanism a little better.
            $targetPropName = $newPropertySettings[$i]->variable_name;
            $sql = "select dp.prop_def_id, dp.id as prop_id from css_networking_network_tree_map ntm
                    inner join css_networking_network_tree nt on ntm.node_id = nt.id
                    inner join css_networking_device d on d.id=nt.device_id
                    inner join css_networking_device_prop_def dpd on dpd.device_type_id=d.type_id
                    inner join css_networking_device_prop dp on dpd.id=dp.prop_def_id
                    WHERE ntm.node_map LIKE  CONCAT('%.', ? ,'.%')
                     AND dpd.variable_name = ?
                     group by dpd.id";
            $stmt = $dbConn->prepare($sql);
            $stmt->bindValue(1, $nodeID, \PDO::PARAM_INT);
            $stmt->bindValue(2, $targetPropName, \PDO::PARAM_STR);
            $stmt->execute();
            $rs = $stmt->fetch(\PDO::FETCH_ASSOC);
                        Log::debug(__METHOD__ . "::WRITING!");
            $newPropertySettings[$i]->prop_def_id = $rs['prop_def_id'];
            $newPropertySettings[$i]->id = $rs['prop_id'];
        }

Log::debug(__METHOD__ . "::WRITING A DEVICE 111!!!!", (array) $newPropertySettings);
Log::debug(__METHOD__ . "::WRITING A DEVICE 222!!!!", (array) $nodeID);

        $handle = fopen("/home/c2-maintenance/sites/unified/storage/logs/log.txt", "a+");
        fwrite($handle, "Test" . "\r\n");
        fclose($handle);

        $deviceService = new \DeviceService();
        $result = $deviceService->WriteDeviceSettings($newPropertySettings, $nodeID, $userID);
        Log::debug(__METHOD__ . "::WRITING A DEVICE 333!!!!", (array) $result);
        return $result;

    }

}
