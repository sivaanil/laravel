<?php

namespace Unified\System\Network;

/**
 * Status of the br0 LAN bridge
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class LANStatus extends InterfaceStatus
{
    private $bridgePortsStatus;

    public function __construct()
    {
        parent::__construct('br0');

        // now get the status of the ports in the bridge
        $ifaceNames = InterfaceHelper::GetInterfaceNames();
        $consoleName = end($ifaceNames);

        $this->brigePortsStatus = array();

        foreach ($ifaceNames as $name) {
            if ($name !== WANConfig::INTERFACE_NAME && $name !== $consoleName) {
                $this->bridgePortsStatus[$name] = new InterfaceStatus($name);
            }
        }
    }

    public function getBridgePortStatus()
    {
        return $this->bridgePortsStatus;
    }
}
