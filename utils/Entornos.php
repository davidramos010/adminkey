<?php


namespace app\utils;


use Yii;
use yii\base\Component;

/**
 * Class Entornos
 *
 * Para tratar los entornos de la aplicación de forma común en todas partes.
 *
 * Se puede llamar directamente o utilizar como un componente:
 * @example Yii::$app->entornos::esPro()
 *
 * @package app\utils
 * @author Josep Vidal
 */
class Entornos extends Component
{
    /**
     * Entornos
     */
    const ENTORNO_DEV = 'dev';
    const ENTORNO_PRE = 'pre';
    const ENTORNO_PRO = 'pro';
    const ENTORNOS_VALIDOS = [self::ENTORNO_DEV, self::ENTORNO_PRE, self::ENTORNO_PRO];

    /**
     * Es entorno de produccion?
     * @return bool
     */
    public static function esPro(): bool
    {
        return self::getEntorno() === self::ENTORNO_PRO;
    }

    /**
     * Es entorno de desarollo?
     * @return bool
     */
    public static function esDev(): bool
    {
        return self::getEntorno() === self::ENTORNO_DEV;
    }

    /**
     * Es entorno de preproduccion?
     * @return bool
     */
    public static function esPre(): bool
    {
        return self::getEntorno() === self::ENTORNO_PRE;
    }

    /**
     * Devuelve el entorno sin tener que recordar el palabro environment
     * @return string
     */
    public static function getEntorno(): string
    {
        return Yii::$app->params['environment'];
    }
}