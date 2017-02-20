<?php

namespace Unified\System\Network;

use Unified\Services\IPAddressHelper;

/**
 * Description of IPv6Subnet
 *
 * @author ross.keatinge
 */
class IPv6Subnet extends SubnetBase
{

    public function IsAddressInRange($address)
    {
        $addressType = $this->ipAddressHelper->GetAddressType($address);

        if ($addressType !== IPAddressHelper::NET_TYPE_IPV6) {
            return false;
        }

        $bin = $this->ipAddressHelper->IPv6ToBinary($address);
        $netBits = substr($bin, 0, $this->maskBitCount);

        return $netBits === $this->netBits;
    }

    protected function SetMaskBitCount()
    {
        if (is_numeric($this->maskAsEntered)) {
            $bitCount = (int) $this->maskAsEntered;

            if ($bitCount > 0 && $bitCount < 128) {
                $this->maskBitCount = $bitCount;
            }
        }
    }

    protected function SetNetAddresses()
    {
        $binInRange = $this->ipAddressHelper->IPv6ToBinary($this->addressInRange);
        $this->netBits = substr($binInRange, 0, $this->maskBitCount);

        $this->netAddressNative = $this->ipAddressHelper->BinaryToIPv6($this->netBits . str_repeat('0', 128 - $this->maskBitCount));
    }

    public function getNetType()
    {
        return IPAddressHelper::NET_TYPE_IPV6;
    }

}
