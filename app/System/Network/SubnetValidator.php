<?php

namespace Unified\System\Network;

/**
 * Represents a subnet for the purpose of validation
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class SubnetValidator
{

    private $ipAddress;
    private $netmask;
    private $gateway;
    private $gatewayRequired;
    private $errors;

    /**
     * 
     * @param string $ipAddress
     * @param string $netmask
     * @param string $gateway
     * @param boolean $gatewayRequired
     */
    public function __construct($ipAddress, $netmask, $gateway, $gatewayRequired)
    {
        $this->ipAddress = $ipAddress;
        $this->netmask = $netmask;
        $this->gateway = $gateway;
        $this->gatewayRequired = $gatewayRequired;
    }

    /**
     * Does lots of validation
     * @return boolean true if configuration is valid
     * @TODO check that the gateway is in the same subnet as the ip address
     */
    public function Validate()
    {
        $this->errors = [];

        if (empty($this->ipAddress)) {
            $this->errors[] = 'IP address is required';
        }

        if (empty($this->netmask)) {
            $this->errors[] = 'Netmask is required';
        }

        if ($this->gatewayRequired && empty($this->gateway)) {
            $this->errors[] = 'Gateway is required';
        }

        if ($this->errors) {
            return false;
        }

        if (filter_var($this->ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            $netType = InterfaceHelper::NET_TYPE_IPV4;
        } else if (filter_var($this->ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            $netType = InterfaceHelper::NET_TYPE_IPV6;
        } else {
            $netType = InterfaceHelper::NET_TYPE_INVALID;
            $this->errors[] = 'IP address is invalid';
        }

        $maskDotted = false;
        $maskInt = false;

        if (filter_var($this->netmask, FILTER_VALIDATE_INT) !== false) {
            $maskInt = true;
        } else if (filter_var($this->netmask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            $maskDotted = true;
        }

        if ($maskDotted && $netType === InterfaceHelper::NET_TYPE_IPV6) {
            $this->errors[] = 'IPv6 subnet cannot have a dotted netmask';
        } else if ($maskInt && $netType === InterfaceHelper::NET_TYPE_IPV4 && ($this->netmask < 1 || $this->netmask > 31)) {
            $this->errors[] = 'IPv4 integer netmask must be between 1 and 31';
        } else if ($maskInt && $netType === InterfaceHelper::NET_TYPE_IPV6 && $this->netmask > 127) {
            $this->errors[] = 'IPv6 netmask must be between 1 and 127. The correct value is usually 64.';
        } else if (!$maskDotted && !$maskInt) {
            $this->errors[] = 'Netmask is invalid';
        }

        if ($this->gatewayRequired) {
            if (empty($this->gateway)) {
                $this->errors[] = 'Gateway is required';
            } else {
                if ($netType === InterfaceHelper::CONFIG_DHCP_IPV4 && !filter_var($this->gateway, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $this->errors[] = 'An IPv4 address must have an IPv4 gateway';
                } else if ($netType === InterfaceHelper::CONFIG_DHCP_IPV6 && !filter_var($this->gateway, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $this->errors[] = 'An IPv6 address must have an IPv6 gateway';
                }
            }
        }

        return count($this->errors) == 0;
    }

    public function getErrorsAsString()
    {
        return implode("\n", $this->errors);
    }

}
