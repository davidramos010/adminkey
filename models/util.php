<?php

namespace app\models;

class util
{
    const SALT = '4dm1nk3y';
    const arrTipoDocumentos = [1=>'DNI',2=>'NIE',3=>'NIF',4=>'PASAPORTE',4=>'OTROS'];

    public static function hash($password) {
        return hash('md5', self::SALT . $password);
    }

    public static function verify($password, $hash) {
        return ($hash == self::hash($password));
    }

    public static function getMoneyFormatedUserToSql($money)
    {
        if (!empty($money)) {
            $money_float = str_replace('€', '', $money);
            $moneyFormat = str_replace(',', '.', str_replace('.', '', $money_float));
        } else {
            $moneyFormat = 0;
        }
        return $moneyFormat;
    }

    public static function getNumberFormatedUserToSql($number)
    {
        if (!empty($number)) {
            $numberFormat = str_replace(',', '.', str_replace('.', '', $number));
        } else {
            $numberFormat = 0;
        }
        return $numberFormat;
    }

    public static function getNumberFormatedSqlToNumber($number)
    {
        if (!empty($number)) {
            $numberFormat = str_replace('.', ',', $number);
        } else {
            $numberFormat = 0;
        }
        return $numberFormat;
    }

    public static function roundDecimal($decimal, $suffix = 2)
    {
        $suffix = $suffix + 1;
        $decimal_suffix = (substr($decimal, 0, strrpos($decimal, '.') + $suffix));
        $param = (substr($decimal, strrpos($decimal, '.') + $suffix, 1));

        return ($param > 0) ? $decimal_suffix + 0.01 : $decimal_suffix;

    }

    public static function getDateFormatedUserToSql($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('d/m/Y', $date);
            $dateFormated = $fecha->format('Y-m-d');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateFormatedSqlToUser($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $date);
            $dateFormated = $fecha->format('d/m/Y');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateFormatedSqlToUserLine($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('d-m-Y H:i', $date);
            $dateFormated = $fecha->format('Y-m-d H:i:s');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateTimeFormatedSqlToUser($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $dateFormated = $fecha->format('d/m/Y H:i:s');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateTimeFormatedUserToSql($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('d/m/Y H:i', $date);
            $dateFormated = $fecha->format('Y-m-d H:i:s');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    /**
     * Retorna una cadena con el contenido en mayusculas
     * @param string $strCadena
     * @return string
     */
    public static function getStringFormatUpper( string $strCadena ):string
    {
       return trim(strtoupper($strCadena));
    }
}