<?php


namespace app\utils;

/**
 * Class Arrays utils
 * @author Josep Vidal
 * @package app\utils
 */
class Arrays
{
    /**
     * Dado un valor y un array de valores devuelve el valor mas cercano al especificado dentro de ese array
     * @param float $valor
     * @param array $arr
     * @return float
     */
    public static function valorMasCercano(float $valor, array $arr) : float
    {
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($valor - $closest) > abs($item - $valor)) {
                $closest = $item;
            }
        }
        return $closest;
    }

    /**
     * Dado un valor y un array de valores devuelve el valor mas cercano al indice del array especificado
     * @param float $valor
     * @param array $arr
     * @return float
     */
    public static function valorMasCercanoPorIndice(float $valor, array $arr) : float
    {
        $closest = null;
        foreach ($arr as $indice => $item) {
            if ($closest === null || abs($valor - $closest) > abs($indice - $valor)) {
                $closest = $indice;
            }
        }
        return $arr[$closest];
    }

    /**
     * Dado un valor y un array de valores devuelve el indice del valor mas cercano al especificado dentro de ese array
     * @param float $valor
     * @param array $arr
     * @return int
     */
    public static function indiceValorMasCercano(float $valor, array $arr) : int
    {
        $closest = null;
        foreach ($arr as $key => $item) {
            if ($closest === null || abs($valor - $closest) > abs($item - $valor)) {
                $closest = $key;
            }
        }
        return $closest;
    }
}