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
        $number = $number - 1;

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $base = strlen($characters);
        $string = '';
        while ($number > 0) {
            $remainder = $number % $base;
            $string = $characters[$remainder] . $string;
            $number = ($number - $remainder) / $base;
        }
        return str_pad($string, 7, 'a', STR_PAD_LEFT);
    }

    function decodeStringToNumber(string $string): int
    {
        if (empty($string)) {
            throw new InvalidArgumentException("Input string cannot be empty");
        }

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $base = strlen($characters);
        $value = 0;
        for ($i = 0; $i < 7; $i++) {
            $value = $value * $base + strpos($characters, $string[$i]);
        }
        return $value + 1;
    }
}
