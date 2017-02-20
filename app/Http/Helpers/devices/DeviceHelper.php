<?php namespace Unified\Http\Helpers\devices;

use DB;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Models\NetworkTree;
use Unified\Models\Device;

class DeviceHelper
{
    public static function getNodeInfo($deviceId)
    {
        $node = NetworkTree::getNodeInfo($deviceId);
        //This function is starting basic add elements as needed for the device.
        return $node[0];
    }

    public static function getDeviceWebLink($nodeId)
    {
        // find the main device
        $node = NetworkTree::find($nodeId);
        $mainDev = NodeHelper::getMainDevNodeFromNodeId($nodeId);
        $device = Device::find($mainDev->device_id);

        //prefix Http or Https
        //ip Address (climb to parent if needed)
        //http port
        $ipAddress = DeviceHelper::getMainIp($node);// getIpaddress();
        $port = DeviceHelper::getWebPort($node);
        $prefix = $port['name'];
        /*if($device->type_id){

        }*/
        $portNum = $port['port'];
        if ($port === false) {
               return 'Error: Web Interface Link Not Found';
        }

        //echo $prefix.$ipAddress.":".$port;
        return $prefix . $ipAddress . ":" . $portNum;
    }

    public static function getMainIp($node)
    {
        $ipAddress = Device::getIPAddress($node->id);
        if (count($ipAddress) == 0) {
            return false;
        }

        return $ipAddress[0]->ip;
    }

    public static function getWebPort($node)
    {
        /*SELECT dp.*,
                            d.`name` AS device_name,
                            d.ip_address as ipAddress,
                            dpd.default_port AS defaultPort,
                            d.type_id,
                            dpd.`name` AS portName
                            FROM css_networking_device_port dp
                            INNER JOIN css_networking_device_port_def dpd ON(dp.port_def_id = dpd.id)
                            INNER JOIN css_networking_device d ON(dp.device_id = d.id)
                            INNER JOIN css_networking_network_tree AS nt ON (dp.device_id = nt.device_id)
                            INNER JOIN css_networking_network_tree_map AS tm ON(nt.id = tm.node_id AND tm.node_map LIKE CONCAT('%.', :nodeid ,'.%')
                        AND tm.deleted = 0 AND tm.build_in_progress = 0)*/
        /*	select `dp`.*, `dpd`.`default_port` as `defaultPort`, `dpd`.`name` as `portName`
        from `css_networking_device_port` as `dp`
        inner join `css_networking_device_port_def` as `dpd` on `dpd`.`id` = `dp`.`port_def_id`
        where dp.device_id = (select node_main_device_id(84612)) and `dpd`.`name` like '%http%'*/

        $port = DevicePort::getPort($node->id);
        //var_dump($port);
        if (count($port) == 0) {
            return false;
        }
        if ($port[0]->tid == "1086" || $port[0]->tid == "1095" || $port[0]->tid == "1283" || $port[0]->tid == "850") {
            $res['name'] = "https";
        } else {
            $res['name'] = $port[0]->portName;
        }
        $res['name'] .= "://";
        $res['port'] = $port[0]->port;

        return $res;
    }

    public static function GetInheritedScanInterval ($nodeId) {
        $results = NetworkTree::getInheritedScanInterval($nodeId);
        foreach ($results as $layer) {
            if (!isset($result['scan_alarms_interval']) && isset($layer->scan_alarms_interval)) {
                $result['scan_alarms_interval'] = $layer->scan_alarms_interval;
            }
            if (!isset($result['scan_properties_interval']) && isset($layer->scan_properties_interval)) {
                $result['scan_properties_interval'] = $layer->scan_properties_interval;
            }

            if (!isset($result['scan_int']) && isset($layer->scan_interval)) {
                $result['scan_int'] = $layer->scan_interval;
            }

            if (!isset($result['fail_threshold']) && isset($layer->fail_threshold)) {
                $result['fail_threshold'] = $layer->fail_threshold;
            }

            if (!isset($result['retry_interval']) && isset($layer->retry_interval)) {
                $result['retry_interval'] = $layer->retry_interval;
            }
            if (!isset($result['fail_count']) && isset($layer->fail_count)) {
                $result['fail_count'] = $layer->fail_count;
            }

            if (!isset($result['fail_alarms_count']) && isset($layer->fail_alarms_count)) {
                $result['fail_alarms_count'] = $layer->fail_alarms_count;
            }

            if (!isset($result['fail_properties_count']) && isset($layer->fail_properties_count)) {
                $result['fail_properties_count'] = $layer->fail_properties_count;
            }
        }
        return $result;

    }

    // TODO = should this always return "not currently scanning?"
    // TODO - We need better documentation in code than ^ that. - ~A! 3/15/2016
    public static function getActiveDeviceScanTime($mainDevId, $nodeId, $scannerPath)
    {
        //SELECT start_timestamp, progress, message from css_networking_scan where device_id = 198300 and progress <> 100 order by id desc;
        /*
         * Disabling temporarily because scanning is disabled and this is slowing things down
         * Will need to reoptimize
         *
		$scans = DB::table('css_networking_scan as dp')
			->select('start_timestamp', 'progress', 'message', 'process_id')
			->where('device_id', '=', $mainDevId)
			->where('progress', '<>','100')
			->orderBy('id', 'desc')
			->get();
		//echo "<br/>";
		//var_dump($scans);
		if($scans!=null){
			//echo "pgrep -f '$scannerPath.*$nodeId'";
			exec("pgrep -f '$scannerPath.*$nodeId'", $output, $return);
			//echo "<br/>";
			//var_dump($output);
			$found = false;
			for($j=0;$j<count($scans);$j++){
				for($i = 0; $i<count($output); $i++){
					//echo "<br/> $output[$i]: {$scans[$j]->process_id}<br/>";
					if(strpos($output[$i], $scans[$j]->process_id)!==false){
						$found = true; //make sure the process is running and it is the correct scanner type.
						break 2;
					}
				}
			}
			if($found){
				return nl2br("Scan Started At: {$scans[$j]->start_timestamp}\n"
				. "Progress: {$scans[$j]->progress}%\n"
				. "Message: {$scans[$j]->message}");
			}
		}
         */
        return "Not Currently Scanning";
    }

}
