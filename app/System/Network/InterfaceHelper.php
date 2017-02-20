<?php

namespace Unified\System\Network;

/**
 * Description of InterfaceHelper
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class InterfaceHelper
{
    const CONFIG_STATIC = 0;
    const CONFIG_DHCP_IPV4 = 4;
    const CONFIG_DHCP_IPV6 = 6;

    const NET_TYPE_INVALID = 0;
    const NET_TYPE_IPV4 = 4;
    const NET_TYPE_IPV6 = 6;
    
    const FILE_INDENT = '    ';

    /**
     * Returns an array of interface names
     * @param string $startingWith Defaults to eth. We usually only want eth0 to ethN
     * @return array
     */
    public static function GetInterfaceNames($startingWith = 'eth')
    {
        $dir = new \DirectoryIterator('/sys/class/net');

        $names = array();

        foreach ($dir as $info) {
            if (!$info->isDot()) {
                $name = $info->getFilename();

                if (empty($startingWith)) {
                    $names[] = $name;
                } else {
                    if (strpos($name, $startingWith) === 0) {
                        $names[] = $name;
                    }
                }
            }
        }

        sort($names);

        return $names;
    }
}
