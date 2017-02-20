<?php

namespace Unified\Devices;
use DB;
use Unified\Models\NetworkTreeMap;


/**
 * Description of DeviceInfo
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class DeviceInfo
{
    /**
     * Get the web URL for a node
     * Returns https if a https port is defined
     * Returns http if http port is defined
     * Returns null if neither port is defined.
     *
     * @param type $nodeId
     * @return string
     */
    public function getWebUrl($nodeId) {

        return NetworkTreeMap::getWebUrl($nodeId);
/*
        $qResult = DB::table('css_networking_network_tree as t')
            ->select('d.ip_address', 'pd.variable_name as protocol', 'p.port')
            ->join('css_networking_device as d', 'd.id', '=', 't.device_id')
            ->join('css_networking_device_port as p', 'p.device_id', '=', 't.device_id')
            ->join('css_networking_device_port_def as pd', 'pd.id', '=', 'p.port_def_id')
            ->where('t.device_id', '=', DB::raw("(select Main_Device_Id(?))"))
            ->setBindings([$nodeId])
            ->get();

        if (empty($qResult)) {
            return null;
        }

        $ipAddress = $qResult[0]->ip_address;

        $httpPort = 80;
        $httpsPort = null;

        foreach($qResult as $info) {
            switch($info->protocol) {
                case 'http':
                    $httpPort = $info->port;
                    break;

                case 'https':
                    $httpsPort = $info->port;
                    break;
            }
        }

        $url = null;

        // prefer https
        // add port only if non-standard
        if ($httpsPort !== null) {
            $url = 'https://' . $ipAddress;

            if ($httpsPort != 443) {
                $url .= ':' . $httpsPort;
            }
        }
        else {
            $url = 'http://' . $ipAddress;

            if ($httpPort != 80) {
                $url .= ':' . $httpPort;
            }
        }

        return $url;

*/
    }
}
