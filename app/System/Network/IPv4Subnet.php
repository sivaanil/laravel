<?php

namespace Unified\System\Network;

use Unified\Services\IPAddressHelper;

/**
 * Description of IPv4Subnet
 *
 * @author ross.keatinge
 */
class IPv4Subnet extends SubnetBase
{

    public function IsAddressInRange($address)
    {
        $addressType = $this->ipAddressHelper->GetAddressType($address);

        if ($addressType !== IPAddressHelper::NET_TYPE_IPV4) {
            return false;
        }

        $bin = $this->ipAddressHelper->IPv4ToBinary($address);
        $netBits = substr($bin, 0, $this->maskBitCount);

        return $netBits === $this->netBits;
    }

    protected function SetMaskBitCount()
    {
        if (is_numeric($this->maskAsEntered)) {
            $bitCount = (int) $this->maskAsEntered;

            if ($bitCount > 0 && $bitCount < 32) {
                $this->maskBitCount = $bitCount;
            }

            return;
        }

        if (filter_var($this->maskAsEntered, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return;
        }

        $bin = $this->ipAddressHelper->IPv4ToBinary($this->maskAsEntered);

        $lastOneIdx = strrpos($bin, '1');

        if ($lastOneIdx === false) {
            return;
        }

        $firstZeroIdx = strpos($bin, '0');

        if ($firstZeroIdx === false || $firstZeroIdx < $lastOneIdx) {
            return;
        }

        $this->maskBitCount = $firstZeroIdx;
    }

    protected function SetNetAddresses()
    {
        $binInRange = $this->ipAddressHelper->IPv4ToBinary($this->addressInRange);
        $this->netBits = substr($binInRange, 0, $this->maskBitCount);

        $this->netAddressNative = $this->ipAddressHelper->BinaryToIPv4($this->netBits . str_repeat('0', 32 - $this->maskBitCount));
    }

    public function getNetType()
    {
        return IPAddressHelper::NET_TYPE_IPV4;
    }

}
