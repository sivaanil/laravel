<?php

namespace Unified\Devices\Rebuild;

/**
 * The information required to build a device.
 * DeviceRebuilder takes this (potentially via a queue) and creates the data required by cswapi rebuilders.
 * 
 * @author Franz Honer <franz.honer@csquaredsystems.com>
 */
class RebuildInfo
{
    public function __construct($nodeId, $deviceId, $rebuildId)
    {
        $this->nodeId = $nodeId;
        $this->deviceId = $deviceId;
        $this->rebuildId = $rebuildId;
    }
    
    private $deviceToken;
    
    public function getDeviceToken()
    {
        if ($this->deviceToken === null) {
            
            $cleanIp = str_replace(['.', ':'], '-', $this->ipAddress);
            
            $this->deviceToken = time() . '-' . $cleanIp . '-' .  mt_rand(0, 999999);
        }

        return $this->deviceToken;
    }

    private $nodeId;

    public function getNodeId()
    {
        return $this->nodeId;
    }

    private $deviceId;

    public function getDeviceId()
    {
        return $this->deviceId;
    }

    private $rebuildId;

    public function getRebuildId()
    {
        return $this->rebuildId;
    }


}
