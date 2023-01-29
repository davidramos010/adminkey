<?php


namespace app\utils;

/**
 * Class HOF => Higher order function
 * @author Josep Vidal
 * @package app\utils
 */
class HOF
{
    /**
     * Número float a formato español : 1211123.12 => 1.211.123,12
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function nfe(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals, ',', '.');
    }
}