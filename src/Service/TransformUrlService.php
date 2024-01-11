<?php

namespace App\Service;

use InvalidArgumentException;

class TransformUrlService
{
    public function encodeNumberToString(int $n): string
    {
        if ($n < 1) {
            throw new InvalidArgumentException("Number must be greater than or equal to 1");
        }

        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $base = strlen($charset);
        $result = '';
        while ($n > 0) {
            $result .= $charset[$n % $base - 1];
            $n = floor($n / $base);
        }
        return str_pad($result, 7, 'a', STR_PAD_LEFT);
    }

    function decodeStringToNumber(string $s): int
    {
        if (empty($s)) {
            throw new InvalidArgumentException("Input string cannot be empty");
        }

        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $base = strlen($charset);
        $result = 0;
        for ($i = 0; $i < 7; $i++) {
            $result = $result * $base + strpos($charset, $s[$i]);
        }
        return $result + 1;
    }
}
