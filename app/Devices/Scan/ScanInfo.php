<?php

namespace Unified\Devices\Scan;

/**
 * Description of ScanInfo
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ScanInfo
{
    const SCAN_TYPE_ALARM = 'A';
    const SCAN_TYPE_PROP = 'P';

    public function __construct($nodeId, $deviceId, $scanId, $scanType, $alreadyScanning)
    {
        $this->nodeId = $nodeId;
        $this->deviceId = $deviceId;
        $this->scanId = $scanId;
        $this->scanType = $scanType;
        $this->alreadyScanning = $alreadyScanning;
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
   
    private $scanId;

    public function getScanId()
    {
        return $this->scanId;
    }
    
    private $scanType;

    public function getScanType()
    {
        return $this->scanType;
    }
    
    public function getScanTypeName()
    {
        $names = ['A' => 'alarm', 'P' => 'prop'];
        
        return isset($names[$this->scanType]) ? $names[$this->scanType] : null;
    }
    
    private $alreadyScanning;
    
    function getAlreadyScanning()
    {
        return $this->alreadyScanning;
    }

}
