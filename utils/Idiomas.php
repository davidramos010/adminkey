<?php
declare(strict_types=1);

namespace app\utils;

use yii\base\Component;

/**
 * Class Idiomas
 * @author Josep Vidal
 * @package app\utils
 */
class Idiomas extends Component
{

    /**
     * Idiomas admitidos
     */
    const IDIOMAS = [self::I_C, self::I_E];
    /**
     * Idioma catalan
     */
    const I_C = 'C';
    /**
     * Idioma castellano
     */
    const I_E = 'E';

    /**
     * @param $idioma
     * @return bool
     */
    public static function esUnIdiomaAdmitido(string $idioma) : bool
    {
        return in_array($idioma, self::IDIOMAS);
    }

    /**
     * @param $idioma
     * @return bool
     */
    public static function esCatalan(string $idioma) : bool
    {
        return self::I_C === $idioma;
    }

    /**
     * @param $idioma
     * @return bool
     */
    public static function esEspanol(string $idioma) : bool
    {
        return self::I_E === $idioma;
    }
}