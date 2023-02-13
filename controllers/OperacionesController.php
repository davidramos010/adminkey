<?php

namespace app\controllers;

use app\components\Tools;
use app\components\ValidadorCsv;
use app\models\Comunidad;
use app\models\Csv;
use app\models\Llave;
use app\models\LlaveUbicaciones;
use app\models\Propietarios;
use app\models\Registro;
use app\models\TipoLlave;
use app\models\TipoLlaveSearch;
use app\utils\Ficheros;
use Yii;
use yii\base\Response;
use yii\console\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;



/**
 * OperacionesController implements the CRUD actions for Llave,Registros model.
 */
class OperacionesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Carga formulario de subida madsiva de llaves
     * @return string
     */
    public function actionLlaves()
    {
        $modelCsv = new Csv();
        return $this->render('llaves', [ 'model'=>$modelCsv ]);
    }

    /**
     * Carga formulario de subida madsiva de llaves
     * @return string
     */
    public function actionRegistros()
    {
        $modelCsv = new Csv();
        return $this->render('registros', [ 'model'=>$modelCsv ]);
    }

    /**
     * Carga de codumentro masivo de llaves
     *
     */
    public function actionAjaxLoadLlaves()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Validamos que sea un csv
        $file = UploadedFile::getInstanceByName('csv_file');
        if ($file->extension !== 'csv') {
            //throw new Exception('No es un fichero .csv!');
            $arrResult['avisos'] = 'No es un fichero .csv!';
            $arrResult['respuesta'] = 'No es un fichero .csv!';
        }
        $filename = Date('Y-m-d') . '-' . time() . '.csv';
        $fullPath = Ficheros::subirFichero($file, sys_get_temp_dir() . '/', $filename);
        //-----
        $arrResult = isset($arrResult['avisos']) ? $arrResult : Llave::setLlavesMasivo($fullPath);

        if(empty($arrResult['avisos'])){
            Yii::$app->session->setFlash('success', Yii::t('yii', $arrResult['respuesta']));
        }else{
            Yii::$app->session->setFlash('warning', Yii::t('yii', $arrResult['respuesta']));
        }

        return $arrResult;
    }


    public function actionAjaxLoadRegistros()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Validamos que sea un csv
        $file = UploadedFile::getInstanceByName('csv_file');
        if ($file->extension !== 'csv') {
            //throw new Exception('No es un fichero .csv!');
            $arrResult['avisos'] = 'No es un fichero .csv!';
            $arrResult['respuesta'] = 'No es un fichero .csv!';
        }
        $filename = Date('Y-m-d') . '-' . time() . '.csv';
        $fullPath = Ficheros::subirFichero($file, sys_get_temp_dir() . '/', $filename);
        //-----
        $arrResult = !isset($arrResult['avisos']) ? Registro::setRegistrosMasivo($fullPath) : $arrResult;

        if(empty($arrResult['avisos'])){
            Yii::$app->session->setFlash('success', Yii::t('yii', $arrResult['respuesta']));
        }else{
            Yii::$app->session->setFlash('warning', Yii::t('yii', $arrResult['respuesta']));
        }

        return $arrResult;
    }

    /**
     * Descargar una plantilla
     * @return false|string|void
     */
    public function actionDescargarPlantillaLlave()
    {
        $fileName = 'REGISTROS_LLAVE.csv';
        $filePath =  Yii::getAlias('@webroot').'/documents/';
        $fullPath = $filePath . $fileName;
        $content = 'DOCUMENTO-NO-DISPONIBLE';
        if (file_exists($filePath . $fileName)) {
            $fileType = Tools::getFileTypeByMime(mime_content_type($fullPath));
            $content = file_get_contents($fullPath);
            if ($fileType == 'csv') {
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen($content));
                header('Content-disposition: inline; filename="' . $fileName . '"');
            }
        }
        return $content;
    }

    /**
     * @return false|string
     */
    public function actionDescargarPlantillaMovimientos()
    {
        $fileName = 'REGISTROS_ENTRADA.csv';
        $filePath =  Yii::getAlias('@webroot').'/documents/';
        $fullPath = $filePath . $fileName;
        $content = 'DOCUMENTO-NO-DISPONIBLE';
        if (file_exists($filePath . $fileName)) {
            $fileType = Tools::getFileTypeByMime(mime_content_type($fullPath));
            $content = file_get_contents($fullPath);
            if ($fileType == 'csv') {
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen($content));
                header('Content-disposition: inline; filename="' . $fileName . '"');
            }
        }
        return $content;
    }


    public function actionPlantillaCargaCsv(): bool
    {
        $rutaCsv = tempnam(sys_get_temp_dir(), "");
        $csv = fopen($rutaCsv, 'w');
        // Construimos cabeceras
        $cabeceras = [
            'nombre_cliente',
            'mail',
            'telefono',
            'idioma',
            'codigo_postal',
            'direccion',
            'origen',
            'orientacion',
            'inclinacion',
            'consumo_anual',
            'tipo_curva',
            'precio_kwh',
            'precio_kwh_excedente',
            'precio_termino_energia',
            'coste_termino_potencia',
            'vida_util_instalacion',
            'potencia_contratada',
            'codigo_agente',
            'marca',
            'localidad',
            'codigo_oferta'
        ];

        fputcsv($csv, $cabeceras, ';');
        fclose($csv);

        header('Content-Disposition: attachment; filename="plantilla-csv-calculadora-solar.csv"');
        header("Content-Type: application/csv");
        header("Content-Length: " . filesize($rutaCsv));
        echo(file_get_contents($rutaCsv));
        return true;
    }

}