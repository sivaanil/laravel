<?php

namespace Unified\System\Network;

use Unified\Services\IPAddressHelper;

/**
 * Represents an IPv4 or IPv6 subnet primarily for the purpose of testing
 * whether or not a given IP address is in the subnet.
 *
 * @author ross.keatinge
 */
abstract class SubnetBase
{

    protected $addressInRange;
    protected $maskAsEntered;
    protected $netAddressNative;
    protected $maskBitCount;
    protected $netBits;
    protected $ipAddressHelper;

    private function __construct($addressInRange, $maskAsEntered, IPAddressHelper $ipAddressHelper)
    {
        $this->addressInRange = $addressInRange;
        $this->maskAsEntered = $maskAsEntered;
        $this->ipAddressHelper = $ipAddressHelper;

        $this->SetMaskBitCount();
        $this->SetNetAddresses();
    }

    abstract protected function SetMaskBitCount();

    abstract protected function SetNetAddresses();

    abstract public function getNetType();

    abstract public function IsAddressInRange($address);

    public function getCIDR()
    {
        return "{$this->netAddressNative}/{$this->maskBitCount}";
    }

    /**
     * Tests for a conflict between an address on this subnet and another subnet
     * 
     * @param string $addressThis Address on this subnet
     * @param string $addressOther Address on the other subnet
     * @param string $maskOther The other subnet's mask
     * @return boolean true if there is a conflict
     */
    public function DoesThisConflict($addressThis, $addressOther, $maskOther)
    {
        // no conflict if they're different address types
        if ($this->ipAddressHelper->GetAddressType($addressOther) !== $this->getNetType()) {
            return false;
        }

        // get the other subnet
        $otherSubnet = self::MakeSubnet($addressOther, $maskOther);

        // we only need find the biggest subnet (least number of bits in mask)
        // and then test if the address from the other network is in that subnet.
        if ($otherSubnet->maskBitCount < $this->maskBitCount) {
            $biggestSubnet = $otherSubnet;
            $testAddress = $addressThis;
        } else {
            $biggestSubnet = $this;
            $testAddress = $addressOther;
        }

        return $biggestSubnet->IsAddressInRange($testAddress);
    }

    /**
     * Make a subnet class of the appropriate type.
     * @param string $addressInRange Any address in the subnet
     * @param type $maskAsEntered The human friendly mask. It can be an integer or dotted for Ipv4
     * @return IPv4Subnet|IPv6Subnet
     */
    public static function MakeSubnet($addressInRange, $maskAsEntered)
    {
        $ipAddressHelper = app()->make('\Unified\Services\IPAddressHelper');

        $addressType = $ipAddressHelper->GetAddressType($addressInRange);

        if ($addressType === null) {
            return null;
        }

        $class = $addressType === IPAddressHelper::NET_TYPE_IPV4 ? IPv4Subnet::class : IPv6Subnet::class;

        return new $class($addressInRange, $maskAsEntered, $ipAddressHelper);
    }

    public function __toString()
    {
        return $this->getCIDR();
    }

}
