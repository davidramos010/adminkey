<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveUbicaciones;
use app\models\Propietarios;
use app\models\TipoLlave;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

use app\utils\HOF;
use Exception;
use GuzzleHttp\Client;
use yii\db\Exception as dbException;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author David Ramos <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoadController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionLoadKeys()
    {
        $n = 1;
        $fileHandler=fopen("web/documents/SGA_CONTROL_CLAUS.csv","r");
        if($fileHandler){
            $first_time = true;
            while($line=fgetcsv($fileHandler,1000)){
                if ($first_time == true) { // primera fila
                    $first_time = false;
                    continue;
                }
                $strCode = $line[0];
                $strOfic = $line[1];
                $strTipo = $line[2];
                $strAcceso = $line[4];
                $numCantidad = (int) $line[5];
                $strNombrePropietario = strtoupper($line[7]);
                $strMovilPropietario = trim($line[8]);
                $numAlarma = strtoupper(trim($line[9]));
                $strAlarma = trim($line[10]);
                $strObservaciones = trim($line[12]);
                //Buscar comunidad
                $arrCode = explode('-',$strCode);

                $strNomenclatura = "C".$arrCode[0];
                $strCodigo = $arrCode[0];
                // Crear llave
                $objNewLlave = new Llave();
                // consultar comunidad
                $objComunidad = Comunidad::find()->where(['id'=>10]);
                die('->'.$objComunidad->createCommand()->rawSql);
                // ubicacion
                $objLlaveUbicacion = LlaveUbicaciones::find()->where(['descripcion_almacen'=>$strOfic])->one();
                // TIPO
                $objLlaveTipo = TipoLlave::find()->where(['descripcion'=>$strTipo])->one();

                // BUSCAR PARTICULAR Y CREARLO
                if($objLlaveTipo->descripcion=='PARTICULAR'){
                    $objParticular = Propietarios::find()->where(['like','nombre_propietario',$strNombrePropietario])->one();
                    if(empty($objParticular)){
                        $objParticular = new Propietarios();
                        $objParticular->nombre_propietario = $strNombrePropietario;
                        $objParticular->direccion = $objComunidad->direccion;
                        $objParticular->cod_postal = $objComunidad->cod_postal;
                        $objParticular->poblacion = $objComunidad->poblacion;
                        $objParticular->telefono = $strMovilPropietario;
                        $objParticular->save();
                    }

                    if(empty($objParticular)){
                        $objNewLlave->id_propietario = $objParticular->id;
                    }
                }

                while ($numCantidad>0){
                    $strCode .= $numCantidad>1 ? '-'.$numCantidad:'';
                    $objNewLlave->id_comunidad = $objComunidad->id;
                    $objNewLlave->id_tipo = $objLlaveTipo->id;
                    $objNewLlave->id_llave_ubicacion = $objLlaveUbicacion->id;
                    $objNewLlave->copia = $numCantidad;
                    $objNewLlave->codigo = $strCode;
                    $objNewLlave->descripcion = $strAcceso;
                    $objNewLlave->alarma = $numAlarma=='SI' ? 1 : 0;
                    $objNewLlave->codigo_alarma = $objNewLlave->alarma ? $strAlarma : NULL;
                    $objNewLlave->observacion = $strObservaciones;
                    if(!$objNewLlave->save()){
                        die('Error:Code:'.$strCode);
                    }
                    echo "->".$objNewLlave->id."\\n";
                    $numCantidad--;
                }


                die('fin');

            }
        }

        return ExitCode::OK;
    }
}
