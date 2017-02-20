<?php

namespace Unified\System\Network;

/**
s * Description of InterfaceConfig
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class InterfaceConfig
{
    private $addressFamily;

    public function getAddressFamily()
    {
        return $this->addressFamily;
    }

    public function setAddressFamily($value)
    {
        $this->addressFamily = $value;
    }

    private $configMethod;

    public function getConfigMethod()
    {
        return $this->configMethod;
    }

    public function setConfigMethod($value)
    {
        $this->configMethod = $value;
    }

    private $ifaceOptions = array();
    private $ifaceOptionsKeyed = array();
    
    public function setIfaceOption($option)
    {
        $this->ifaceOptions[] = $option;
        
        $spacePos = strpos($option, ' ');
        
        if ($spacePos !== false) {
            $key = substr($option, 0, $spacePos);
            $value = substr($option, $spacePos + 1);
            
            $this->ifaceOptionsKeyed[$key] = $value;
        }
    }

    public function getIfaceOptions()
    {
        return $this->ifaceOptions;
    }

    public function getIfaceOption($key)
    {
        if (array_key_exists($key, $this->ifaceOptionsKeyed)) {
            return $this->ifaceOptionsKeyed[$key];
        }
        
        return null;
    }

    private $otherRows = array();

    public function setOtherRow($row)
    {
        $this->otherRows = $row;
    }
}
