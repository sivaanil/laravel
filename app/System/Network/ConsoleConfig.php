<?php

namespace Unified\System\Network;

use Unified\System\CommandHelper;

/**
 * Reads and writes the configuration for the console interface.
 * On the front panel, this port is labeled LAN. The naming was changed after this code was written.
 * This is always eth1.
 * 
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ConsoleConfig
{

    public function __construct()
    {
        $this->ReadFromSystem();
    }

    const INTERFACE_NAME = 'eth1';

    private $ipAddress;

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setIpAddress($value)
    {
        $this->ipAddress = $value;
    }

    private $netmask;

    public function getNetmask()
    {
        return $this->netmask;
    }

    public function setNetmask($value)
    {
        $this->netmask = $value;
    }

    private function ReadFromSystem()
    {
        $reader = new InterfaceConfigReader();
        $config = $reader->getInterfaceConfig('console');

        $this->ipAddress = $config->getIfaceOption('address');
        $this->netmask = $config->getIfaceOption('netmask');
    }

    /**
     * Writes new config to /tmp/console.conf, copies it to /etc/network/interfaces.d and restarts the interface
     * @throws NetworkConfigException
     */
    public function Save()
    {
        $subnet = new SubnetValidator($this->ipAddress, $this->netmask, null, false);

        if (!$subnet->Validate()) {
            throw new NetworkConfigException($subnet->getErrorsAsString());
        }

        /* @var $consoleSubnet \Unified\System\Network\SubnetBase */
        $consoleSubnet = SubnetBase::MakeSubnet($this->ipAddress, $this->netmask);
        $wanConfig = new WANConfig();

        if ($consoleSubnet->DoesThisConflict($this->ipAddress, $wanConfig->getIpAddress(), $wanConfig->getNetmask())) {
            throw new NetworkConfigException('Management port subnet conflicts with WAN');
        }

        $rows = array();

        $family = strpos($this->ipAddress, ':') === false ? 'inet' : 'inet6';

        $ifaceName = self::INTERFACE_NAME;
        $indent = InterfaceHelper::FILE_INDENT;

        $rows[] = "auto $ifaceName";
        $rows[] = "iface $ifaceName $family static";
        $rows[] = "$indent address {$this->ipAddress}";
        $rows[] = "$indent netmask $this->netmask";

        $tmpFile = sys_get_temp_dir() . '/console.conf';
        file_put_contents($tmpFile, implode("\n", $rows));
        chmod($tmpFile, 0600);

        CommandHelper::CallWrapper('copyconsole');
        CommandHelper::CallWrapper("restartiface-{$ifaceName}");
    }

}
