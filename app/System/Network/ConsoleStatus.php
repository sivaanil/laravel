<?php

namespace Unified\System\Network;

/**
 * Description of WANStatus
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ConsoleStatus extends InterfaceStatus
{
    public function __construct()
    {
        parent::__construct('eth1');
    }
}
