<?php

namespace Unified\Devices\Build;

/**
 * Generic object to keep state of a BuildProgress object and enumerate statuses.
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class BuildProgress
{
    const STATUS_NOT_FOUND = 0;
    const STATUS_BUILDING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_FAILED = 3;
    const STATUS_CANCELED = 4;

    public function __construct($status, $percentage = null, $message = null, $processId = null, $nodeId = null)
    {
        $this->status = $status;
        $this->percentage = $percentage;
        $this->message = $message;
        $this->processId = $processId;
        $this->nodeId = $nodeId;
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

    private $nodeId;

    public function getNodeId()
    {
        return $this->nodeId;
    }
}
