<?php

namespace Unified\Devices\Rebuild;

/**
 * Generic object to keep state of a BuildProgress object and enumerate statuses.
 *
 * @author Franz Honer <franz.honer@csquaredsystems.com>
 */
class RebuildProgress
{
    const STATUS_NOT_FOUND = 0;
    const STATUS_REBUILDING = 1;
    const STATUS_COMPLETE = 2;

    public function __construct($status, $percentage = null, $message = null, $processId = null, $nodeId = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->nodeId = $nodeId;
    }

    private $status;

    public function getStatus()
    {
        return $this->status;
    }

    private $message;

    public function getMessage()
    {
        return $this->message;
    }

    private $nodeId;

    public function getNodeId()
    {
        return $this->nodeId;
    }
}
