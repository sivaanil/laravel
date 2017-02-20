<?php

namespace Unified\System\Network;

use Unified\System\CommandHelper;

/**
 * Reads and writes the configuration for the LAN bridge, br0
 * On the front panel, these ports are numbered 1,2,3 ...
 * The port labeled LAN is not one of these. It is what the our code calls console.
 * The naming was changed after this code was written.
 * 
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class LANConfig
{

    public function __construct()
    {
        $this->ReadFromSystem();
    }

    const INTERFACE_NAME = 'br0';

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
        $config = $reader->getInterfaceConfig('lan');

        $this->ipAddress = $config->getIfaceOption('address');
        $this->netmask = $config->getIfaceOption('netmask');
    }

    /**
     * Save the LAN (device ports) subnet
     * 
     * @param ConsoleConfig $consoleConfig
     *   We use this for validation / conflict detection because LAN and console settings are on the same page.
     *   It should be the console (management) port settings before they are saved.
     *   The controller should try to save LANConfig first and then ConsoleConfig, catching exceptions.
     * @throws NetworkConfigException
     */
    public function Save(ConsoleConfig $consoleConfig)
    {
        $subnet = new SubnetValidator($this->ipAddress, $this->netmask, null, false);

        if (!$subnet->Validate()) {
            throw new NetworkConfigException($subnet->getErrorsAsString());
        }

        /* @var $lanSubnet SubnetBase */
        $lanSubnet = SubnetBase::MakeSubnet($this->ipAddress, $this->netmask);
        $wanConfig = new WANConfig();

        if ($lanSubnet->DoesThisConflict($this->ipAddress, $wanConfig->getIpAddress(), $wanConfig->getNetmask())) {
            throw new NetworkConfigException('Device ports subnet conflicts with WAN');
        }

        if ($lanSubnet->DoesThisConflict($this->ipAddress, $consoleConfig->getIpAddress(), $consoleConfig->getNetmask())) {
            throw new NetworkConfigException('Device and management port subnets conflict.');
        }
        
        $rows = array();

        $family = strpos($this->ipAddress, ':') === false ? 'inet' : 'inet6';

        // Every eth* interface except eth0 (WAN) and eth1 (console) is included in the bridge
        $ifaceNames = InterfaceHelper::GetInterfaceNames();

        $bridgePorts = array();

        foreach ($ifaceNames as $name) {

            if ($name !== WANConfig::INTERFACE_NAME && $name !== ConsoleConfig::INTERFACE_NAME) {
                $rows[] = "iface $name $family manual";
                $bridgePorts[] = $name;
            }
        }

        $rows[] = '';

        $indent = InterfaceHelper::FILE_INDENT;
        $rows[] = 'auto br0';
        $rows[] = "iface br0 $family static";
        $rows[] = "$indent bridge_ports " . implode(' ', $bridgePorts);
        $rows[] = "$indent address {$this->ipAddress}";
        $rows[] = "$indent netmask $this->netmask";

        $tmpFile = sys_get_temp_dir() . '/lan.conf';
        file_put_contents($tmpFile, implode("\n", $rows));
        chmod($tmpFile, 0600);

        CommandHelper::CallWrapper('copylan');
        CommandHelper::CallWrapper('restartiface-br0');
    }

}
