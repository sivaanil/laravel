<?php

namespace Unified\Devices\Scan;

/**
 * Description of ScanProgress
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ScanProgress
{
    const STATUS_NOT_FOUND = 0;
    const STATUS_STARTING = 1;
    const STATUS_SCANNING = 2;
    const STATUS_COMPLETE = 3;
    const STATUS_FAILED = 4;

    public function __construct($status, $percentage = null, $message = null, $processId = null)
    {
        $this->status = $status;
        $this->percentage = $percentage;
        $this->message = $message;
        $this->processId = $processId;
    }

    private $status;
    
    public function getStatus()
    {
        return $this->status;
    }

    private $percentage;
    
    public function getPercentage()
    {
        return $this->percentage;
    }
    
    private $message;
    
    public function getMessage()
    {
        return $this->message;
    }
    
    private $processId;
    
    public function getProcessId()
    {
        return $this->processId;
    }
}
