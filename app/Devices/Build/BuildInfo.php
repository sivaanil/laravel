<?php

namespace Unified\Devices\Build;

/**
 * The information required to build a device.
 * DeviceBuilder takes this (potentially via a queue) and creates the data required by cswapi builders.
 * 
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class BuildInfo
{
    private $deviceToken;
    
    public function getDeviceToken()
    {
        if ($this->deviceToken === null) {
            
            $cleanIp = str_replace(['.', ':'], '-', $this->ipAddress);
            
            $this->deviceToken = time() . '-' . $cleanIp . '-' .  mt_rand(0, 999999);
        }

        return $this->deviceToken;
    }

    private $typeId;

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    private $name;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    private $parentNodeId;

    public function setParentNodeId($parentNodeId)
    {
        $this->parentNodeId = $parentNodeId;
        return $this;
    }

    public function getParentNodeId()
    {
        return $this->parentNodeId;
    }

    private $ipAddress;

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    private $ports = [];

    public function setPorts($ports)
    {
        $this->ports = $ports;
        return $this;
    }

    public function getPorts()
    {
        return $this->ports;
    }

    public function setPort($name, $value)
    {
        $this->ports[$name] = $value;
        return $this;
    }

    public function getPort($name)
    {
        return isset($this->ports[$name]) ? $this->ports[$name] : null;
    }

    private $ipAddress2;

    public function getIpAddress2()
    {
        return $this->ipAddress2;
    }

    public function setIpAddress2($ipAddress2)
    {
        $this->ipAddress2 = $ipAddress2;
        return $this;
    }

    private $scanInterval;

    public function getScanInterval()
    {
        return $this->scanInterval;
    }

    public function setScanInterval($scanInterval)
    {
        $this->scanInterval = $scanInterval;
        return $this;
    }

    private $snmpVersion;

    public function getSnmpVersion()
    {
        return $this->snmpVersion;
    }

    public function setSnmpVersion($snmpVersion)
    {
        $this->snmpVersion = $snmpVersion;
        return $this;
    }

    private $readCommunity;
    
    public function getReadCommunity()
    {
        return $this->readCommunity;
    }

    public function setReadCommunity($readCommunity)
    {
        $this->readCommunity = $readCommunity;
        return $this;
    }

    private $writeCommunity;
    
    public function getWriteCommunity()
    {
        return $this->writeCommunity;
    }

    public function setWriteCommunity($writeCommunity)
    {
        $this->writeCommunity = $writeCommunity;
        return $this;
    }
    
    private $webUiUserName;
    
    public function getWebUiUserName()
    {
        return $this->webUiUserName;
    }

    public function setWebUiUserName($webUiUserName)
    {
        $this->webUiUserName = $webUiUserName;
        return $this;
    }

    private $webUiPassword;
    
    public function getWebUiPassword()
    {
        return $this->webUiPassword;
    }

    public function setWebUiPassword($webUiPassword)
    {
        $this->webUiPassword = $webUiPassword;
        return $this;
    }

    // the following are only relevant for SNMP v3

    private $snmpAuthType;

    public function getSnmpAuthType()
    {
        return $this->snmpAuthType;
    }

    public function setSnmpAuthType($snmpAuthType)
    {
        $this->snmpAuthType = $snmpAuthType;
        return $this;
    }

    private $snmpUserName;

    public function getSnmpUserName()
    {
        return $this->snmpUserName;
    }

    public function setSnmpUserName($snmpUserName)
    {
        $this->snmpUserName = $snmpUserName;
        return $this;
    }

    private $snmpAuthPassword;

    public function getSnmpAuthPassword()
    {
        return $this->snmpAuthPassword;
    }

    public function setSnmpAuthPassword($snmpAuthPassword)
    {
        $this->snmpAuthPassword = $snmpAuthPassword;
        return $this;
    }

    private $snmpAuthEncryption;

    public function getSnmpAuthEncryption()
    {
        return $this->snmpAuthEncryption;
    }

    public function setSnmpAuthEncryption($snmpAuthEncryption)
    {
        $this->snmpAuthEncryption = $snmpAuthEncryption;
        return $this;
    }

    private $snmpPrivPassword;

    public function getSnmpPrivPassword()
    {
        return $this->snmpPrivPassword;
    }

    public function setSnmpPrivPassword($snmpPrivPassword)
    {
        $this->snmpPrivPassword = $snmpPrivPassword;
        return $this;
    }

    private $snmpPrivEncryption;

    public function getSnmpPrivEncryption()
    {
        return $this->snmpPrivEncryption;
    }

    public function setSnmpPrivEncryption($snmpPrivEncryption)
    {
        $this->snmpPrivEncryption = $snmpPrivEncryption;
        return $this;
    }

    // end of SNMP v3
}
