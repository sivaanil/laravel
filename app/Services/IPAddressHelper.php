<?php

namespace Unified\Services;

/**
 * Common functions to help with validating IP addresses and subnets
 * Binary "numbers" are represented as strings of 1 and 0.
 * 
 * @author ross.keatinge
 */
class IPAddressHelper
{

    const NET_TYPE_IPV4 = 4;
    const NET_TYPE_IPV6 = 6;

    private $binaryHelper;

    public function __construct(BinaryHelper $binaryHelper)
    {
        $this->binaryHelper = $binaryHelper;
    }

    /**
     * Takes a valid IP address and returns a 32 or 128 bit binary number
     * 
     * @param string $address
     * @return string of 1 and 0
     */
    public function IPToBinary($address)
    {
        return $this->IsIPv6($address) ? $this->IPv6ToBinary($address) : $this->IPv4ToBinary($address);
    }

    /**
     * Takes a valid IPv4 address and returns a 32 bit binary number
     * 
     * @param string $address
     * @return string of 1 and 0
     */
    public function IPv4ToBinary($address)
    {
        $bytes = explode('.', $address);

        $bin = '';

        foreach ($bytes as $byte) {
            $bin .= $this->binaryHelper->DecToBin($byte, 8);
        }

        return $bin;
    }

    /**
     * Takes a 32 bit binary number and returns an IPv4 address xxx.xxx.xxx.xxx format
     * 
     * @param string
     * @return string
     */
    public function BinaryToIPv4($binary)
    {
        $decimal = [];

        for ($idx = 0; $idx < strlen($binary); $idx += 8) {
            $decimal[] = bindec(substr($binary, $idx, 8));
        }

        return implode('.', $decimal);
    }

    /**
     * Takes a 128 binary number and returns an IPv6 address xxxx:xxxx:xxxx:xxxx:xxxx:xxxx:xxxx:xxxx format
     * @todo Provide an option to convert to short format using :: if there are groups of 0000
     * 
     * @param string
     * @return string
     */
    public function BinaryToIPv6($binary)
    {
        $hex = [];

        for ($idx = 0; $idx < strlen($binary); $idx += 16) {
            $hex[] = $this->binaryHelper->BinToHex(substr($binary, $idx, 16), 4);
        }

        return implode(':', $hex);
    }

    /**
     * Takes a valid IPv6 address and returns a 128 bit binary number
     * 
     * @param type $address
     * @return string of 1 and 0
     */
    public function IPv6ToBinary($address)
    {
        $address = $this->IPv6AddZeros($address);
        $parts = explode(':', $address);

        $bin = '';

        foreach ($parts as $part) {
            $bin .= $this->binaryHelper->HexToBin($part, 16);
        }

        return $bin;
    }

    /**
     * Replace :: with appropriate blocks of zeros in an iPv6 address
     * 
     * @param string $address
     * @return string
     */
    public function IPv6AddZeros($address)
    {
        if (strpos($address, '::') === false) {
            return $address;
        }

        $colonCount = substr_count($address, ':');
        $isOnEnd = (substr($address, -2) === '::');

        if ($isOnEnd) {
            $replacement = str_repeat(':0000', 9 - $colonCount);
        } else {
            $replacement = str_repeat(':0000', 8 - $colonCount) . ':';
        }

        return str_replace('::', $replacement, $address);
    }

    /**
     * Returns true if supplied IPv4 or IPv6 address is valid
     * 
     * @param string $address
     * @return boolean
     */
    public function IsValid($address)
    {
        return filter_var($address, FILTER_VALIDATE_IP) === false ? false : true;
    }

    /**
     * Returns type of address or null if invalid
     * 
     * @param type $address
     * @return type
     */
    public function GetAddressType($address)
    {
        if ($this->IsValidIPv4($address)) {
            return self::NET_TYPE_IPV4;
        }

        if ($this->IsValidIPv6($address)) {
            return self::NET_TYPE_IPV6;
        }

        return null;
    }

    public function IsValidIPv4($address)
    {
        return (bool) filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    public function IsValidIPv6($address)
    {
        return (bool) filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public function IsIPv6($address)
    {
        return strpos($address, ':') === false ? false : true;
    }

}
