<?php

namespace Unified\System;

/**
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class CommandHelper
{
    /**
     * Call the wrapper that lets us run a command as root.
     * @param type $cmd
     */
    public static function CallWrapper($cmd)
    {
        $cmd = escapeshellarg($cmd);
        exec("/usr/local/bin/sitegate/sitegate-wrapper $cmd");
    }
}
