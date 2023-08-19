<?php

namespace app\utils;

/**
 * Class Validadores
 * @package app\utils
 */
class Validadores
{
    /**
     * @param string $cups
     * @return bool
     */
    public static function isCUPS(string $cups): bool
    {
        $format = preg_match('/^ES[0-9]{16}[A-Z]{2}$/i', $cups);

        if ($format) {
            $CUPS_16 = substr($cups, 2, 16);
            $control = substr($cups, 18, 2);
            $letters = [
                'T',
                'R',
                'W',
                'A',
                'G',
                'M',
                'Y',
                'F',
                'P',
                'D',
                'X',
                'B',
                'N',
                'J',
                'Z',
                'S',
                'Q',
                'V',
                'H',
                'L',
                'C',
                'K',
                'E'
            ];
            $fmod = fmod($CUPS_16, 529);
            $imod = intval($fmod);

            $quotient = $imod / 23;
            $remainder = $imod % 23;

            $dc1 = $letters[$quotient];
            $dc2 = $letters[$remainder];
            return ($control == $dc1 . $dc2);

        }
        return false;
    }

    /**
     * @param string $nif
     * @return bool
     */
    public static function isNif(string $nif): bool
    {
        $formatch = preg_match('/^[0-9]{8}[A-Z]{1}$/i', $nif);
        return ($formatch) ? true : false;
    }

    /**
     * @param string $dni
     * @return bool
     */
    public static function isDni(string $dni): bool
    {
        $formatch = preg_match('/^[XYZ]?([0-9]{7,8})([A-Z])$/i', $dni);
        return ($formatch) ? true : false;
    }

    /**
     * @param string $cif
     * @return bool
     */
    public static function isCif(string $cif): bool
    {
        $formatch = preg_match('/([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/i', $cif);
        return ($formatch) ? true : false;
    }

    /**
     * @param string $nie
     * @return bool
     */
    public static function isNie(string $nie): bool
    {
        $formatch = preg_match('/^[XYZ]\d{7,8}[A-Z]$/i', $nie);
        return ($formatch) ? true : false;
    }

    /**
     * Validamos cuenta corriente
     * @param string $CCC
     * @return bool|false|int
     */
    public static function isCCC(string $CCC): bool
    {
        $formatch = preg_match('/^[0-9]{20}$/', $CCC);
        return $formatch && self::isStrictCcc($CCC);
    }

    /**
     * Validamos IBAN
     * @param $IBAN
     * @return bool|false|int
     */
    public static function isIBAN(string $IBAN): bool
    {
        $formatch = preg_match('/^ES[0-9]{22}$/i', $IBAN);
        return $formatch && self::isStrictIban($IBAN);
    }

    /**
     * @param $ccc
     * @return bool
     */
    public
    static function isStrictCcc(
        $ccc
    ): bool {
        $sum = $ccc[0] * 4 + $ccc[1] * 8 + $ccc[2] * 5 + $ccc[3] * 10 + $ccc[4] * 9 + $ccc[5] * 7 + $ccc[6] * 3 + $ccc[7] * 6;
        $dc1 = 11 - ($sum % 11);

        if ($dc1 == 10) {
            $dc1 = 1;
        } else {
            if ($dc1 == 11) {
                $dc1 = 0;
            }
        }

        if ($dc1 != $ccc[8]) {
            return false;
        }

        $sum = $ccc[10] + $ccc[11] * 2 + $ccc[12] * 4 + $ccc[13] * 8 + $ccc[14] * 5 + $ccc[15] * 10 + $ccc[16] * 9 + $ccc[17] * 7 + $ccc[18] * 3 + $ccc[19] * 6;
        $dc2 = 11 - ($sum % 11);

        if ($dc2 == 10) {
            $dc2 = 1;
        } else {
            if ($dc2 == 11) {
                $dc2 = 0;
            }
        }

        if ($dc2 != $ccc[9]) {
            return false;
        }

        return true;
    }

    /**
     * @param $IBAN
     * @return bool
     */
    public static function isStrictIban($IBAN): bool
    {
        $ccc = substr($IBAN, 4, 20);
        if (!self::isStrictCcc($ccc)) {
            return false;
        }

        $dc = substr($IBAN, 2, 2);
        $IBAN26 = $ccc . '1428' . $dc;
        $IBAN14 = substr($IBAN26, 0, 14);
        $fmod = fmod($IBAN14, 97);
        $imod = intval($fmod);

        $dc = str_pad($imod, 2, '0', STR_PAD_LEFT);
        $IBAN12 = substr($IBAN26, 14, 12);
        $fmod = fmod($dc . $IBAN12, 97);
        $imod = intval($fmod);

        return ($imod == 1);
    }

    /**
     * Valida que un string sea un base64
     * @param $base64
     * @return bool
     */
    public static function isBase64Encoded($base64)
    {
        return preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $base64);
    }
}