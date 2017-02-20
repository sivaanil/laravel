<?php

namespace Unified\System\Network;

/**
 * Represents the status of an interface as reported by the /sys/class/net psuedo files
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class InterfaceStatus
{
    private $name;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->ReadFromSystem();
    }

    public function getName()
    {
        return $this->name;
    }

    private $macAddress;

    public function getMacAddress()
    {
        return $this->macAddress;
    }

    private $linkStatus;

    public function getLinkStatus()
    {
        return $this->linkStatus;
    }

    private $speed;

    public function getSpeed()
    {
        return $this->speed;
    }

    private $duplex;

    public function getDuplex()
    {
        return $this->duplex;
    }

    private function ReadFromSystem()
    {
        $basePath = '/sys/class/net/' . $this->name . '/';
        
        if (!file_exists($basePath)) {
            return;
        }
        
        $path = $basePath . 'address';
        if (is_readable($path)) {
            $this->macAddress = trim(file_get_contents($path));
        }
        
        $path = $basePath . 'speed';
        if (is_readable($path)) {
            $this->speed = trim(file_get_contents($path));
        }

        $path = $basePath . 'operstate';
        if (is_readable($path)) {
            $this->linkStatus = trim(file_get_contents($path));
        }
        
        $path = $basePath . 'duplex';
        if (is_readable($path)) {
            $this->duplex = trim(file_get_contents($path));
        }
    }
}
