<?php

namespace app\models;

use app\components\ValidadorCsv;
use app\utils\Ficheros;
use app\utils\Strings;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "llave".
 *
 * @property int $id
 * @property int|null $id_comunidad
 * @property int|null $id_tipo
 * @property int|null $id_propietario
 * @property int|null $id_llave_ubicacion
 * @property int|null $copia
 * @property string|null $codigo
 * @property string|null $descripcion
 * @property string|null $observacion
 * @property int|null $activa
 * @property int|null $alarma
 * @property string|null $codigo_alarma
 * @property string|null $nomenclatura
 * @property int|null $facturable
 *
 * @property Comunidad $comunidad
 * @property LlaveStatus[] $llaveStatuses
 * @property Registro[] $registros
 * @property TipoLlave $tipo
 * @property Propietarios $propietarios
 * @property LlaveUbicaciones $llaveUbicaciones
 */
class Llave extends \yii\db\ActiveRecord
{

    public $llaveLastStatus = null;
    public $nombre_propietario = null;
    public $cliente_comunidad = null;
    public $nomenclatura = null;

    public $comercial = null;
    public $responsable = null;
    public $observacion = null;

    public $total = null;
    public $salida = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'id_llave_ubicacion', 'id_tipo','descripcion'], 'required', 'message'=> Yii::t('yii',  'Es requerido')],
            [['id_comunidad', 'id_tipo', 'id_propietario', 'id_llave_ubicacion','copia', 'activa','alarma','facturable'], 'integer'],
            [['copia'], 'integer', 'max' => 99],
            [['codigo','nombre_propietario','nomenclatura','cliente_comunidad'], 'string', 'max' => 100],
            [['descripcion', 'observacion','codigo_alarma'], 'string', 'max' => 255],
            /*[['codigo'], 'unique'],*/
            [['id_comunidad'], 'exist', 'skipOnError' => true, 'targetClass' => Comunidad::className(), 'targetAttribute' => ['id_comunidad' => 'id']],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoLlave::className(), 'targetAttribute' => ['id_tipo' => 'id']],
            [['id_propietario'], 'exist', 'skipOnError' => true, 'targetClass' => Propietarios::className(), 'targetAttribute' => ['id_propietario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_comunidad' => Yii::t('app','Comunidad'),
            'id_tipo' => Yii::t('app','Tipo'),
            'copia' => Yii::t('app','Copia'),
            'codigo' => Yii::t('app','Codigo'),
            'descripcion' => Yii::t('app','Descripcion'),
            'observacion' => Yii::t('app','Observacion'),
            'activa' => Yii::t('app','Activa'),
            'facturable' => Yii::t('app','Facturable'),
            'alarma' => Yii::t('app','Alarma')
        ];
    }

    /**
     * Gets query for [[comunidad]].
     *
     * @return \yii\db\ActiveQuery|ComunidadQuery
     */
    public function getComunidad()
    {
        return $this->hasOne(Comunidad::className(), ['id' => 'id_comunidad']);
    }

    /**
     * Gets query for [[LlaveStatuses]].
     *
     * @return \yii\db\ActiveQuery|LlaveStatusQuery
     */
    public function getLlaveStatuses()
    {
        return $this->hasMany(LlaveStatus::className(), ['id_llave' => 'id']);
    }

    /**
     * Ultimo estado de una llave
     * @return \yii\db\ActiveQuery
     */
    public function getLlaveLastStatus()
    {
        return $this->hasOne(LlaveStatus::className(), ['id_llave' => 'id'])->orderBy(['fecha'=>SORT_DESC]);
    }

    /**
     * Gets query for [[Registros]].
     *
     * @return \yii\db\ActiveQuery|RegistroQuery
     */
    public function getRegistros()
    {
        return $this->hasMany(Registro::className(), ['id_llave' => 'id']);
    }

    /**
     * Gets query for [[Tipo]].
     *
     * @return \yii\db\ActiveQuery|TipoLlaveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(TipoLlave::className(), ['id' => 'id_tipo']);
    }

    /**
     * Gets query for [[Propietario]].
     *
     * @return \yii\db\ActiveQuery|Propietarios
     */
    public function getPropietarios()
    {
        return $this->hasOne(Propietarios::className(), ['id' => 'id_propietario']);
    }

    /**
     * retorno de la variable de nomenclatura
     * @return string|null
     */
    public function getNomenclatura()
    {
        return $this->nomenclatura = !empty($this->id_comunidad) ? $this->comunidad->nomenclatura : 'P'.$this->propietarios->id;
    }


    /**
     * {@inheritdoc}
     * @return LlaveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LlaveQuery(get_called_class());
    }

    /**
     * Lista de comunidades activas
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getComunidadesDropdownList()
    {
        $query = "SELECT id, nombre FROM comunidad WHERE estado=1 order by nombre";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre');
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getTipoLlaveDropdownList()
    {
        $query = "SELECT id, descripcion FROM tipo_llave order by descripcion";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'descripcion');
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getUbicacionDropdownList()
    {
        $query = "SELECT id, CONCAT(tipo_almacen, ' ', descripcion_almacen) as descripcion_almacen FROM llave_ubicaciones order by descripcion_almacen ASC";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'descripcion_almacen');
    }

    public static function getPropietariosDropdownList()
    {
        $query = "SELECT pp.id, (CASE
                                    WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                                    WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                                    ELSE NULL
                                END) as nombre_propietario 
                    FROM propietarios pp ORDER BY nombre_propietario ASC, nombre_representante ASC ";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre_propietario');
    }

    /**
     * Busca el siguente codigo
     * @return int
     */
    public function getNext() {

        if(!empty($this->id_comunidad) && !empty($this->nomenclatura)){
            $resultData = $this->find()->select(["MAX(SUBSTRING(codigo,6,3)) as codigo","COUNT(1) as total"])->where(['like','codigo',substr($this->nomenclatura,1,3).'-' ])->one();
        }

        if(!empty($this->id_comunidad) && empty($this->nomenclatura)){
            $resultData = $this->find()->select(["MAX(SUBSTRING(codigo,6,3)) as codigo","COUNT(1) as total"])->where(['id_comunidad' => $this->id_comunidad])->one();
        }

        if(empty($resultData) && !empty($this->id_propietario)){
            $resultData = $this->find()->select(["MAX(SUBSTRING(codigo,6,3)) as codigo","COUNT(1) as total"])->where(['id_propietario' => $this->id_propietario])->one();
        }

        $strCode = empty($resultData) || empty($resultData->codigo) ? '' : (int) $resultData->codigo;
        $numCantidad = (int) empty($resultData) || empty($resultData->total) ? 0 : (int) $resultData->total;
        $numNext = !empty($strCode) && (int) $strCode<$numCantidad ? $strCode : $numCantidad;

        return str_pad($numNext + 1, 3, '0', STR_PAD_LEFT) ;
    }

    /**
     * Crear siguiente copia
     * @return string
     */
    public function getNextCopi(): array
    {
        $strCodeKey = '';
        $numCopies = 0;
        if(!empty($this->codigo)){
            $strCodeKey = substr($this->codigo,0,8);
            $resultData = $this->find()->where(['like','codigo', $strCodeKey])->orderBy('copia DESC')->one();
            if($resultData->copia>=1){
                $numCopies = $resultData->copia+1;
                $strCodeKey = $strCodeKey.'-'.$numCopies;
            }
        }

        return ['copia'=>$numCopies, 'codigo'=>$strCodeKey] ;
    }

    /**
     * Buscar llaves por estado en un rango de fechas
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getDataHome(): array
    {
        $searchModelStatus = new LlaveStatusSearch();
        // Cantidad de llave y llaves prestadas
        $arrParam['llaves'] = Llave::getInfoDashboard();
        // --------------------------------------------
        // Lista de llaves prestadas
        $searchModelStatus->status = 'S';
        $arrParam['llavesDataProvider'][5] = (count($arrParam['llaves']['arrLlavesFecha'][5])) ? $searchModelStatus->searchBetween([], 5) : null;
        $arrParam['llavesDataProvider'][10] = (count($arrParam['llaves']['arrLlavesFecha'][10])) ? $searchModelStatus->searchBetween([], 10) : null;
        $arrParam['llavesDataProvider'][15] = (count($arrParam['llaves']['arrLlavesFecha'][15])) ? $searchModelStatus->searchBetween([], 15) : null;
        // --------------------------------------------
        // Contador de llaves
        $arrParam['llavesDataProvider']['cliente'] = $searchModelStatus->searchDataByCliente();
        $arrParam['llavesDataProvider']['propietario'] = $searchModelStatus->searchDataByPropietario();
        return $arrParam;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getInfoDashboard():array
    {
        $query = Yii::$app->db;
        // --------------------------
        // Cantidad de llaves activas
        $numLlaves = (int) Llave::find()->where(['activa'=>1])->count();
        // ---------------------------
        // Array de llaves con salida
        $queryString = '
            SELECT ls.id_llave ,ls.id AS lastid, ls.status
            FROM llave_status ls
            INNER JOIN  llave_status ls2 ON (ls2.id = ls.id and ls2.id_llave = ls.id_llave and ls2.id = (
                SELECT st.id FROM llave_status st WHERE st.id_llave = ls.id_llave ORDER BY st.fecha DESC limit 1
                ))
            WHERE ls.status ="S"; ';
        $resultadosSalida = $query->createCommand($queryString)->queryAll();
        $numLlavesSalida = (int) count($resultadosSalida);
        $porcLlavesSalida = ($numLlaves>0 && $numLlavesSalida>0)? round((float) ((100/$numLlaves)*$numLlavesSalida),2):0;
        $arrLlavesFecha[5] =  self::getLlavesStatusRangoFecha('S',5);
        $arrLlavesFecha[10] = self::getLlavesStatusRangoFecha('S',10);
        $arrLlavesFecha[15] = self::getLlavesStatusRangoFecha('S',15);
        // --------------------------
        return ['num_llaves'=>$numLlaves,'porcentaje_salida'=>$porcLlavesSalida,'num_salida'=>$numLlavesSalida,'arr_salida'=>$resultadosSalida,'arrLlavesFecha'=>$arrLlavesFecha];
    }

    /**
     * Consulta llaves con salidas de hace mas de 5,10 y 15 dias y sin devolución
     * @param string $strStatus
     * @param int $numDias
     * @return array
     */
    public static function getLlavesStatusRangoFecha(string $strStatus = 'S' , int $numDias=15 ): array
    {
        $query = Yii::$app->db;
        $fecha_actual = date("d-m-Y");

        switch ($numDias){
            case 5:
                $strFechaConsultaIni = date("Y-m-d",strtotime($fecha_actual."- ".$numDias." days"));
                $strFechaConsultaFin = date("Y-m-d",strtotime($fecha_actual."- ".($numDias+5)." days"));
                break;

            case 10:
                $strFechaConsultaIni = date("Y-m-d",strtotime($fecha_actual."- ".($numDias+1)." days"));
                $strFechaConsultaFin = date("Y-m-d",strtotime($fecha_actual."- ".($numDias+5)." days"));
                break;

            case 15:
                $strFechaConsultaIni = date("Y-m-d",strtotime($fecha_actual."- ".$numDias." days"));
                $strFechaConsultaFin = null;
                break;

        }
        // ---------------------------
        // Generar rangos
        $strAddWere = (!empty($strFechaConsultaFin))? " ls.fecha >='".$strFechaConsultaFin." 00:00:00' AND ":" ";

        // ---------------------------
        // Array de llaves con salida en el rango de fechas
        $queryString = "
            SELECT ls.id_llave ,ls.id AS lastid, ls.status,ls.fecha
            FROM llave_status ls
            INNER JOIN  llave_status ls2 ON (ls2.id = ls.id and ls2.id_llave = ls.id_llave and ls2.id = (
                SELECT st.id FROM llave_status st WHERE st.id_llave = ls.id_llave ORDER BY st.fecha DESC limit 1
                ))
            WHERE ls.status ='".$strStatus."' and  $strAddWere ls.fecha <='".$strFechaConsultaIni." 23:00:00'; ";
        $resultadosSalida = $query->createCommand($queryString)->queryAll();
     return $resultadosSalida;
    }

    /**
     * @param string $fullPath
     * @return array
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Calculation\Exception
     */
    public static function setLlavesMasivo(string $fullPath ): array
    {
        $avisos = [];
        $llavesOK = 0;
        $llavesTotal = 0;
        // ------------------------------
        // Validamos contenido del CSV
        $validador = new ValidadorCsv($fullPath);
        $validador->validarCabeceras(
            [
                'CODIGO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                    ValidadorCsv::RULE_CAN_BE_NULL => true
                ],
                'OFICINA' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                    ValidadorCsv::RULE_CAN_BE_NULL => false
                ],
                'TIPO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                    ValidadorCsv::RULE_CAN_BE_NULL => false,
                    ValidadorCsv::RULE_LIMITED_TO => ['COMUNITAT', 'PROPIETARI', 'COMUNIDAD', 'PARTICULAR']
                ],
                'ACCESO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => false
                ],
                'CANTIDAD' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_BIGGER_THAN,
                    ValidadorCsv::RULE_CAN_BE_NULL => false,
                    ValidadorCsv::RULE_BIGGER_THAN => 0
                ],
                'PROPIETARIO_CODIGO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                    ValidadorCsv::RULE_CAN_BE_NULL => true
                ],
                'PROPIETARIO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => true,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                ],
                'ALARMA' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => false,
                    ValidadorCsv::RULE_LIMITED_TO => ['SI', 'NO', 'si', 'no', 'Si', 'No']
                ],
                'CODIGO_ALARMA' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => true
                ],
                'CONTRATO' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => false,
                    ValidadorCsv::RULE_LIMITED_TO => ['SI', 'NO', 'si', 'no', 'Si', 'No']
                ],
                'COMENTARIOS' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_MIN_LENGTH => 3,
                    ValidadorCsv::RULE_CAN_BE_NULL => true
                ],
                'MOVIL' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => true,
                    ValidadorCsv::RULE_MIN_LENGTH => 6,
                ],
                'DIRECCION' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => true,
                    ValidadorCsv::RULE_MIN_LENGTH => 1,
                ],
                'CODIGO_POSTAL' => [
                    ValidadorCsv::RULE_TYPE => ValidadorCsv::RULE_TYPE_STRING,
                    ValidadorCsv::RULE_CAN_BE_NULL => true,
                    ValidadorCsv::RULE_MIN_LENGTH => 5,
                ],
            ]
        );
        // ------------------------------
        $validador->validarContenido();
        // Si falla alguna validación le tiramos el error
        if ($errors = $validador->getErrors(ValidadorCsv::ERROR_USER)) {
            //throw new Exception(join($errors, '<br>'));
            $avisos[] = join('<br>',$errors);
        }
        // ------------------------------
        if (empty($avisos)) {
            foreach ($validador->getRows() as $file_key => $line) {
                $strNomenclatura = "";
                $numFilaArchivo = ($file_key+2);
                $strCode = $line['CODIGO'];
                $strOfic = $line['OFICINA'];
                $strTipo = $line['TIPO'];
                $strAcceso = utf8_decode($line['ACCESO']);
                $numCantidad = (int)$line['CANTIDAD'];
                $strCodPropietario = strtoupper($line['PROPIETARIO_CODIGO']);
                $strNombrePropietario = strtoupper(utf8_decode($line['PROPIETARIO']));
                $strMovil = trim($line['MOVIL']);
                $numAlarma = strtoupper(trim($line['ALARMA']));
                $strAlarma = trim($line['CODIGO_ALARMA']);
                $strFacturable = trim($line['CONTRATO']);
                $strObservaciones = trim(utf8_decode($line['COMENTARIOS']));
                $strDireccion = trim(utf8_decode($line['DIRECCION']));
                $strCodigoPostal = trim($line['CODIGO_POSTAL']);
                $llavesTotal += $numCantidad;
                // ------------------------------
                $objComunidad = NULL;
                $strCodigo = "";
                // ------------------------------
                if (!empty($strCode)) { //COMUNIDAD
                    $isContainString = strpos($strCode, 'C');
                    //Buscar comunidad
                    $strPrefijo = ($strTipo == 'PARTICULAR')?"P":"C";
                    $arrCode = explode('-', $strCode);
                    $strNomenclatura = ($isContainString === false) ? $strPrefijo : "";
                    $strNomenclatura .= $arrCode[0];
                    $strCodigo = isset($arrCode[1]) ? $arrCode[1] : '';
                    // consultar comunidad
                    $objComunidad = Comunidad::find()->where(['nomenclatura' => $strNomenclatura])->one();
                }
                // ------------------------------
                if ($strTipo == 'PARTICULAR' && (!empty($strCodPropietario) || !empty($strNombrePropietario))) {
                    if (!empty($strCodPropietario)) {// buscar por codigo
                        $strCodPropietario = str_replace('P', '', $strCodPropietario);
                        $objParticular = Propietarios::find()->where(['id' => (int)$strCodPropietario])->one();
                    } else { // buscar por nombre
                        $objParticular = Propietarios::find()->where(['like', 'nombre_propietario', $strNombrePropietario])->one();
                    }
                    // Si no tiene registros los crea
                    if (empty($objParticular)) {
                        //Conultar codpostal y provincia
                        $strDireccion = (isset($objComunidad) && !empty($objComunidad->direccion))?$objComunidad->direccion:$strDireccion;
                        $strDireccion = empty($strDireccion)?'PENDIENTE':$strDireccion;
                        $strCodigoPostal = (isset($objComunidad) && !empty($objComunidad->cod_postal))?$objComunidad->cod_postal:$strCodigoPostal;
                        $objCodPostal = Codipostal::find()->where(['cp'=>$strCodigoPostal])->one();
                        $strPoblacion = (isset($objCodPostal) && !empty($objCodPostal->provincia))?$objCodPostal->provincia:'';
                        $strPoblacion = (empty($strPoblacion) && isset($objComunidad) && isset($objComunidad->poblacion))?$objComunidad->poblacion:'';
                        $strMovil = empty($strMovil)?'999999999':$strMovil;
                        // ------------------------------
                        $objParticular = new Propietarios();
                        $objParticular->nombre_propietario = $strNombrePropietario;
                        $objParticular->direccion = $strDireccion;
                        $objParticular->cod_postal = $strCodigoPostal;
                        $objParticular->poblacion = $strPoblacion;
                        $objParticular->telefono = $strMovil;
                        $objParticular->movil = $strMovil;
                        if ($objParticular->save()) {
                            $strCodigo = "001";
                            $avisos[] = 'Alerta en la linea:' . $numFilaArchivo . ' - Completar los datos de Propietario:' . $strNombrePropietario . '<br>';
                        } else {
                            $avisos[] = 'Error en la linea:' . $numFilaArchivo . ' - No encuentra datos del Propietario:' . $strNombrePropietario . '<br>';
                            continue;
                        }
                    }
                    // ------------------------------
                    // Asignacion de codigo a la llave
                    if(isset($objParticular) && !empty($objParticular->id) && empty($strCode)){
                        $strNomenclatura = "P".str_pad($objParticular->id, 3, '0', STR_PAD_LEFT);
                    }
                }else{
                    $objParticular = null;
                }
                // ------------------------------ Ubicacion
                $objLlaveUbicacion = LlaveUbicaciones::find()->where(['descripcion_almacen' => $strOfic])->one();
                // ------------------------------ TIPO
                $objLlaveTipo = TipoLlave::find()->where(['descripcion' => $strTipo])->one();
                // VALIDATE
                if ((empty($objComunidad) && empty($objParticular)) || empty($objLlaveUbicacion) || empty($objLlaveTipo)) {
                    $avisos[] = 'Error en la linea:' . $numFilaArchivo . ' - Validar datos de la Comunidad / Ubicacion / Tipo Llave' . '<br>';
                    continue;
                }
                // -------------------------------------------------
                while ($numCantidad > 0) {
                    // Crear llave
                    $objNewLlave = new Llave();
                    $objNewLlave->id_comunidad = empty($objComunidad)? $objComunidad : $objComunidad->id;
                    $objNewLlave->id_tipo = $objLlaveTipo->id;
                    $objNewLlave->id_llave_ubicacion = $objLlaveUbicacion->id;
                    $objNewLlave->copia = $numCantidad;
                    $objNewLlave->descripcion = $strAcceso;
                    $objNewLlave->alarma = $numAlarma == 'SI' ? 1 : 0;
                    $objNewLlave->codigo_alarma = $objNewLlave->alarma ? $strAlarma : NULL;
                    $objNewLlave->observacion = $strObservaciones;
                    $objNewLlave->facturable = ($strFacturable == 'SI') ? 1 : 0;
                    if (isset($objParticular) && isset($objParticular->id)) {
                        $objNewLlave->id_propietario = $objParticular->id;
                    }
                    // Asignación de ccdigo de la llave
                    $strCodigo = empty($strCodigo) ? (string) $objNewLlave->getNext() : $strCodigo;// si es vacio consulta el siguente registro
                    $strCodigoLlave = $strNomenclatura . "-" . $strCodigo;
                    $strCodigoLlave .= ($numCantidad > 1) ? '-' . $numCantidad : '';
                    $objNewLlave->codigo = $strCodigoLlave;
                    try {
                        if (!$objNewLlave->save()) {
                            //die('Error:Code:' . $strCodigo);
                            $avisos[] = 'Error en la linea:' . $numFilaArchivo . ' - Imposible crear la llave.<br>';
                        } else {
                            $llavesOK++;
                        }
                    } catch (\yii\db\Exception $e) {
                        $strMensaje = 'Error en la linea:' . $numFilaArchivo . '::' . $e->getMessage() . '<br>';
                        if ($e->getCode() == 23000) {
                            $strMensaje = 'Error en la linea:' . $numFilaArchivo . ' - Imposible crear la llave, el codigo ya existe (' . $strCodigoLlave . ').<br>';
                        }
                        $avisos[] = $strMensaje;
                    }
                    $numCantidad--;
                }
            }
        }
        // -------------------------------------------------
        if (empty($avisos)) {
            $strMensaje = 'Almacenado Correctamente!!';
            Yii::$app->session->setFlash('success', Yii::t('yii', $strMensaje));
        } else {
            $strMensaje = 'No fue posible actualizar los todos registros !! ' . $llavesOK . ' de ' . $llavesTotal . ' Registrados correctamente.<br>';
        }
        // -------------------------------------------------
        return [
            'respuesta' => $strMensaje,
            'avisos' => $avisos,
            'error' => $avisos,
            'errors' => [],
        ];
    }

    /**
     * @param $sql
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public static function findBySql($sql, $params = [])
    {
        return parent::findBySql($sql, $params); // TODO: Change the autogenerated stub
    }


}
