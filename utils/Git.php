<?php

namespace app\utils;

use Yii;

/**
 * Class Git
 * @package app\utils
 */
class Git
{
    /**
     * Devuelve la versión actual de la aplicación
     * @return string
     */
    public static function getCurrentVersion()
    {
        if (Entornos::esDev()) {
            $path = Yii::getAlias('@app');
            `git rev-parse --abbrev-ref HEAD > $path/version`;
        }
        return 'V.' . file_get_contents(Yii::getAlias('@app/version'));
    }
}