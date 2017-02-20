<?php

namespace Unified\System\Network;

/**
 * Status of the eth0 WAN interface
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class WANStatus extends InterfaceStatus
{
    public function __construct()
    {
        parent::__construct('eth0');
    }
}
