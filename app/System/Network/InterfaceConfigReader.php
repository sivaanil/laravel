<?php

namespace Unified\System\Network;

/**
 * Description of NetConfigReader
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class InterfaceConfigReader
{
    const INTERFACES_DIR = '/etc/network/interfaces.d';

    public function getInterfaceConfig($fileName)
    {
        $filePath = self::INTERFACES_DIR . '/' . $fileName . '.conf';

        $iface = new InterfaceConfig();

        if (!file_exists($filePath)) {
            return $iface;
        }

        $contents = file_get_contents($filePath);
        $rawRows = explode("\n", $contents);

        $gotIface = false;

        foreach ($rawRows as $row) {
            // read a row and clean it so whitespace is one space only
            $row = $this->CleanRow($row);

            if ($gotIface) {
                // we have read the iface row so these are options for the iface
                $iface->setIfaceOption($row);
            }
            // is this the iface?
            else if (substr($row, 0, 5) === 'iface') {
                $parts = explode(' ', $row);
                $count = count($parts);

                if ($count < 2) {
                    // ignore this, it makes no sense.
                } else {
                    if ($count > 2) {   // inet or inet6
                        $iface->setAddressFamily($parts[2]);
                    }

                    if ($count > 3) {   // static or dhcp
                        $iface->setConfigMethod($parts[3]);
                    }
                }

                $gotIface = true;
            } else {
                $iface->setOtherRow($row);
            }
        }

        return $iface;
    }

    /**
     * Clean a row from the interfaces file
     * @param string $row
     * @return string
     */
    private static function CleanRow($row)
    {
        $row = strtolower(trim($row));

        // all whitespace within the line should be a single space
        $parts = preg_split('/\s+/', $row);
        return implode(' ', $parts);
    }

}
