<?php

namespace App\Service;

use InvalidArgumentException;

class TransformUrlService
{
    public function encodeNumberToString(int $number): string
    {
        if ($number < 1) {
            throw new InvalidArgumentException("Number must be greater than or equal to 1");
        }

        $base = 26;
        $result = "";

        while ($number > 0) {
            $remainder = ($number - 1) % $base;
            $result = chr(ord('a') + $remainder) . $result;
            $number = intdiv(($number - 1), $base);
        }

        return str_pad($result, 8, 'a', STR_PAD_LEFT);
    }
}
