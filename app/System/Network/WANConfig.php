<?php

namespace Unified\System\Network;

use Unified\System\CommandHelper;

/**
 * Reads and writes the configuration for the WAN interface, eth0
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class WANConfig
{

    public function __construct()
    {
        $this->ReadFromSystem();
    }

    const INTERFACE_NAME = 'eth0';

    private $dhcp;

    /**
     * @return int
     */
    public function getDhcp()
    {
        return $this->dhcp;
    }

    /**
     * @param int $value
     */
    public function setDhcp($value)
    {
        $this->dhcp = $value;
    }

    private $ipAddress;

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $value
     */
    public function setIpAddress($value)
    {
        $this->ipAddress = $value;
    }

    private $netmask;

    /**
     * @return string
     */
    public function getNetmask()
    {
        return $this->netmask;
    }

    /**
     * @param string $value
     */
    public function setNetmask($value)
    {
        $this->netmask = $value;
    }

    private $gateway;

    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param string $value
     */
    public function setGateway($value)
    {
        $this->gateway = $value;
    }

    private $dns1;

    /**
     * @return string
     */
    public function getDns1()
    {
        return $this->dns1;
    }

    /**
     * @param string $value
     */
    public function setDns1($value)
    {
        $this->dns1 = $value;
    }

    private $dns2;

    /**
     * @return string
     */
    public function getDns2()
    {
        return $this->dns2;
    }

    /**
     * @param string $value
     */
    public function setDns2($value)
    {
        $this->dns2 = $value;
    }

    /**
     * Reads current config from the interfaces.d/wan.conf file
     */
    private function ReadFromSystem()
    {
        $reader = new InterfaceConfigReader();
        $config = $reader->getInterfaceConfig('wan');

        if ($config->getConfigMethod() === 'dhcp') {
            $this->dhcp = $config->getAddressFamily() === 'inet6' ? InterfaceHelper::CONFIG_DHCP_IPV6 : InterfaceHelper::CONFIG_DHCP_IPV4;
        } else {
            $this->dhcp = InterfaceHelper::CONFIG_STATIC;
        }

        $this->ipAddress = $config->getIfaceOption('address');
        $this->netmask = $config->getIfaceOption('netmask');
        $this->gateway = $config->getIfaceOption('gateway');

        $dns = $config->getIfaceOption('dns-nameservers');

        if (!empty($dns)) {
            $dns = explode(' ', $dns);

            $this->dns1 = $dns[0];

            if (count($dns) > 1) {
                $this->dns2 = $dns[1];
            }
        }
    }

    /**
     * Writes new config to /tmp/wan.conf
     */
    public function Save()
    {
        $this->Validate();

        switch ($this->dhcp) {
            case InterfaceHelper::CONFIG_STATIC:
                $family = strpos($this->ipAddress, ':') === false ? 'inet' : 'inet6';
                break;

            case InterfaceHelper::CONFIG_DHCP_IPV4:
                $family = 'inet';
                break;

            case InterfaceHelper::CONFIG_DHCP_IPV6:
                $family = 'inet6';
                break;
        }

        $configMethod = $this->dhcp === InterfaceHelper::CONFIG_STATIC ? 'static' : 'dhcp';

        $rows = array();
        $rows[] = 'auto eth0';
        $rows[] = "iface eth0 $family $configMethod";

        if ($this->dhcp === InterfaceHelper::CONFIG_STATIC) {

            $indent = InterfaceHelper::FILE_INDENT;

            $rows[] = "$indent address {$this->ipAddress}";
            $rows[] = "$indent netmask {$this->netmask}";
            $rows[] = "$indent gateway {$this->gateway}";

            $dns = array();

            if (!empty($this->dns1)) {
                $dns[] = $this->dns1;
            }

            if (!empty($this->dns2)) {
                $dns[] = $this->dns2;
            }

            if ($dns) {
                $dns = implode(' ', $dns);
                $rows[] = "$indent dns-nameservers $dns";
            }
        }

        $tmpFile = sys_get_temp_dir() . '/wan.conf';
        file_put_contents($tmpFile, implode("\n", $rows));
        chmod($tmpFile, 0600);

        CommandHelper::CallWrapper('copywan');
        CommandHelper::CallWrapper('restartiface-eth0');
    }

    private function Validate()
    {
        if ($this->dhcp === InterfaceHelper::CONFIG_DHCP_IPV4 || $this->dhcp === InterfaceHelper::CONFIG_DHCP_IPV6) {
            return;
        }

        if ($this->dhcp !== InterfaceHelper::CONFIG_STATIC) {
            throw new NetworkConfigException('Invalid DHCP setting');
        }

        $subnet = new SubnetValidator($this->ipAddress, $this->netmask, $this->gateway, true);

        if (!$subnet->Validate()) {
            throw new NetworkConfigException($subnet->getErrorsAsString());
        }

        /* @var $wanSubnet \Unified\System\Network\SubnetBase */
        $wanSubnet = SubnetBase::MakeSubnet($this->ipAddress, $this->netmask);

        if (!$wanSubnet->IsAddressInRange($this->gateway)) {
            throw new NetworkConfigException('IP address and gateway are in different subnets');
        }

        $lanConfig = new LANConfig();

        if ($wanSubnet->DoesThisConflict($this->ipAddress, $lanConfig->getIpAddress(), $lanConfig->getNetmask())) {
            throw new NetworkConfigException('WAN configuration conflicts with device ports.');
        }

        $consoleConfig = new ConsoleConfig();

        if ($wanSubnet->DoesThisConflict($this->ipAddress, $consoleConfig->getIpAddress(), $consoleConfig->getNetmask())) {
            throw new NetworkConfigException('WAN configuration conflicts with maintenance port.');
        }

        $ipFlag = strpos($this->ipAddress, ':') === false ? FILTER_FLAG_IPV4 : FILTER_FLAG_IPV6;

        if (!empty($this->dns1)) {
            if (filter_var($this->dns1, FILTER_VALIDATE_IP, $ipFlag) === false) {
                throw new NetworkConfigException('DNS 1 setting is invalid');
            }
        }

        if (!empty($this->dns2)) {
            if (filter_var($this->dns2, FILTER_VALIDATE_IP, $ipFlag) === false) {
                throw new NetworkConfigException('DNS 2 setting is invalid');
            }
        }
    }

}
