<?php


namespace app\utils;

use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class Ficheros utils
 * @author Josep Vidal
 * @package app\utils
 */
class Ficheros
{
    /**
     * Guarda un fichero, cargado con la clase UploadedFile, en la ruta especificada con el nombre especificado
     * @param $file UploadedFile
     * @param string $path
     * @param string $filename
     * @return string
     * @throws \yii\base\Exception
     */
    public static function subirFichero(UploadedFile $file, string $path, string $filename)
    {
        $filePath = $path . $filename;
        FileHelper::createDirectory($filePath);
        $file->saveAs($filePath . $filename);
        return $filePath . $filename;
    }
}