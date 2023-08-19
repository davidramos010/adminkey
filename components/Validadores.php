<?php


namespace app\components;

/**
 * Class Validadores
 * @package app\components
 */
class Validadores
{
    /**
     * Validamos cuenta corriente
     * @param $CCC
     * @return bool|false|int
     */
    public static function is_CCC($CCC)
    {
        $formatch = preg_match('/^[0-9]{20}$/', $CCC);
        return $formatch && self::is_strict_CCC($CCC);
    }

    /**
     * Validamos IBAN
     * @param $IBAN
     * @return bool|false|int
     */
    public static function is_IBAN($IBAN)
    {
        $formatch = preg_match('/^ES[0-9]{22}$/i', $IBAN);
        return $formatch && self::is_strict_IBAN($IBAN);
    }

    /**
     * @param $ccc
     * @return bool
     */
    public
    static function is_strict_CCC(
        $ccc
    ) {
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
    public static function is_strict_IBAN($IBAN)
    {
        $ccc = substr($IBAN, 4, 20);
        if (!self::is_strict_CCC($ccc)) {
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

}