<?php


namespace app\utils;

/**
 * Strings
 * Utils relacionados con las strings
 * @package app\utils
 * @author Josep Vidal
 */
class Strings
{
    /**
     * Dado un string elimina, en caso de existir, la contrabarra inicial
     * @param string $string
     * @return string
     */
    public static function eliminarContrabarra(string $string): string
    {
        $firstChar = substr($string, 0, 1);

        if (in_array($firstChar, ['/', '\\'])) {
            return substr($string, 1);
        }

        return $string;
    }
}