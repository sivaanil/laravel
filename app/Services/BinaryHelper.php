<?php

namespace Unified\Services;

/**
 * Functions for binary numbers between decimal and hex for manipulating IP addresses.
 * Binary "numbers" are represented by strings of 1 and 0.
 *
 * @author ross.keatinge
 */
class BinaryHelper
{

    public function DecToBin($dec, $width)
    {
        return $this->BaseConvert($dec, 10, 2, $width);
    }

    public function HexToBin($hex, $width)
    {
        return $this->BaseConvert($hex, 16, 2, $width);
    }

    public function BinToHex($bin, $width)
    {
        return $this->BaseConvert($bin, 2, 16, $width);
    }

    private function BaseConvert($fromValue, $fromBase, $toBase, $width)
    {
        $unpadded = base_convert($fromValue, $fromBase, $toBase);

        return sprintf("%0{$width}s", $unpadded);
    }

}
