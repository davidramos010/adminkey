<?php

namespace app\models;

use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "registro".
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_llave
 * @property int|null $id_comercial
 * @property string|null $entrada
 * @property string|null $salida
 * @property string|null $observacion
 * @property string|null $firma_soporte
 * @property Llave $llave
 * @property User $user
 * @property Comerciales $comerciales
 * @property string|null $tipo_documento
 * @property string|null $documento
 * @property string|null $nombre_responsable
 * @property string|null $telefono
 */
class Registro extends \yii\db\ActiveRecord
{
    public $codigo = null;
    public $username = null;
    public $clientes = null;//cliente
    public $propietarios = null;
    public $comercial = null;
    public $nombre_propietario = null;
    public $llaves = null;
    public $llaves_e = null;
    public $llaves_s = null;
    public $fecha_registro = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user', 'id_llave', 'id_comercial','tipo_documento'], 'integer'],
            [['entrada', 'salida','signature'], 'safe'],
            [['documento','telefono'], 'string', 'max' => 20],
            [['nombre_responsable', 'observacion','codigo','username','firma_soporte'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_llave'], 'exist', 'skipOnError' => true, 'targetClass' => Llave::className(), 'targetAttribute' => ['id_llave' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_llave' => 'Id Llave',
            'id_comercial' => 'Id Comercial',
            'entrada' => 'Entrada',
            'salida' => 'Salida',
            'observacion' => 'Observacion',
            'id_' => 'Observacion',
            'firma_soporte' => 'Firma Soporte',
            'tipo_documento' => 'Tipo Documento',
            'documento' => 'Documento',
            'nombre_reponsable' => 'Nombre Responsable',
            'telefono' => 'Teléfono',
        ];
    }

    /**
     * Gets query for [[Llave]].
     *
     * @return \yii\db\ActiveQuery|LlaveQuery
     */
    public function getLlave()
    {
        return $this->hasOne(Llave::className(), ['id' => 'id_llave']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     *
     * Gets query for [[Comerciales]].
     * @return \yii\db\ActiveQuery
     */
    public function getComerciales()
    {
        return $this->hasOne(Comerciales::className(), ['id' => 'id_comercial']);
    }

    /**
     * {@inheritdoc}
     * @return RegistroQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegistroQuery(get_called_class());
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getComercialesDropdownList()
    {
        $query = "SELECT id, nombre FROM comerciales order by nombre";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre');
    }

    /**
     * @return string|null
     */
    public function getFechaRegistro(){
        $this->fecha_registro = (!empty($this->entrada))?$this->entrada:null;
        $this->fecha_registro = (empty($this->fecha_registro))?$this->salida:$this->fecha_registro;
        return $this->fecha_registro;
    }

    /**
     * @param int $numIdRegistro
     * @return void
     */
    public function getInfoRegistro(int $numIdRegistro){
        $objRegistro = self::findOne(['id'=>$this->id]);
        $objLlaves =   $this->getInfoByParams(['id'=>$this->id]);
        $objComercial = Comerciales::findOne(['id'=>$objRegistro->id_comercial]);
        return ['registro'=>$objRegistro,'llaves'=>$objLlaves,'comercial'=>$objComercial];
    }

    /**
     * Esta funcion retorna datos de la llave
     * @param $params
     * @return Registro[]|array|null
     */
    public function getInfoByParams($params)
    {
        $query = LlaveStatus::find()->alias('sta');
        $query->select([
            'sta.id as id',
            'sta.fecha as fecha_registro',
            'r.firma_soporte',
            'll.codigo',
            'll.descripcion as descripcion_llave',
            'u.username',
            'cm.nombre as comercial',
            'sta.status as status',
            "com.nombre as clientes",
            "(CASE
                WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                ELSE NULL
              END) as nombre_propietario"
        ]);
        $query->leftJoin('registro r','sta.id_registro = r.id');
        $query->leftJoin('llave ll','sta.id_llave = ll.id');
        $query->leftJoin('User u','r.id_user = u.id');
        $query->leftJoin('comerciales cm','r.id_comercial = cm.id');
        $query->leftJoin('propietarios p','p.id = ll.id_propietario');
        $query->leftJoin('comunidad com','com.id = ll.id_comunidad');

        if(!empty( $this->id )){
            $query->where(['r.id' => $this->id]);
            $query->andWhere('sta.id_registro =r.id');
        }

        $query->orderBy('r.id DESC');

        return $query->all();
    }


    /**
     * Retrono HTML de certificado de entrga y/o devolución
     * @return string
     */
    public function getHtmlAceptacion(array $arrParams){

        $objComercial = $arrParams['comercial'];
        $objRegistro = $arrParams['registro'];
        $strFirma =  (!empty($objRegistro->firma_soporte))?"<img src='".Url::to('@app/web/firmas/'.$objRegistro->firma_soporte)."' width='150'>":"";

        $arrResponsable['nombre'] = trim(strtoupper($objRegistro->nombre_responsable));
        $arrResponsable['documento'] = (!empty($objRegistro->documento) && !empty($objRegistro->tipo_documento) && isset(util::arrTipoDocumentos[$objRegistro->tipo_documento]))?util::arrTipoDocumentos[$objRegistro->tipo_documento].' ':'';
        $arrResponsable['documento'] .= trim(strtoupper($objRegistro->documento));
        $arrResponsable['telefono'] = trim(strtoupper($objRegistro->telefono));

        $strDivResponsableText = "";
        $strDivResponsableText .= empty($objComercial->nombre) ? "":"<strong>".$objComercial->nombre."</strong><br>";
        $strDivResponsableText .= empty($objComercial->direccion) ? "":$objComercial->direccion."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($objComercial->cod_postal) ? "":$objComercial->cod_postal."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($objComercial->poblacion) ? "":$objComercial->poblacion."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($objComercial->email) ? "":"Email: ".$objComercial->email."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($objComercial->email) ? "":"Teléfono: ".$objComercial->telefono."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($objComercial->email) ? "":"Movíl: ".$objComercial->movil."<br/>";
        $strDivResponsableText .= "<strong>Responsable</strong><br/>";
        $strDivResponsableText .= empty($arrResponsable['documento']) ? "":$arrResponsable['documento'] ."&nbsp;&nbsp;";
        $strDivResponsableText .= empty($arrResponsable['nombre']) ? "":$arrResponsable['nombre'] ."<br/>";
        $strDivResponsableText .= empty($arrResponsable['telefono']) ? "":$arrResponsable['telefono'] ;

        $addHtmlRows = '';
        if(count($arrParams['llaves'])){
            foreach ($arrParams['llaves'] as $valueLlave){
                $valueLlave->status = ($valueLlave->status=='E')?'Entrada':'Salida';
                $addHtmlRows .="<tr>
                                 <td>".$valueLlave->status."</td>
                                 <td>".$valueLlave->codigo."</td>
                                 <td>".$valueLlave->descripcion_llave."</td>
                                 <td>".$valueLlave->clientes."</td>
                                 <td>".$valueLlave->nombre_propietario."</td>
                                </tr>";
            }
        }

        $strHtmlHeader = " <!-- info row -->
                            <div class=\"row invoice-info\">
                              <div class=\"col-12 invoice-col\">
                                <div class=\"table-responsive\">
                                  <table class=\"table\">
                                   <tr>
                                     <td align='center'><h3><address>".Yii::$app->params['empresa']."</address></h3></td>
                                   </tr>
                                    <tr>
                                      <td align='center'>
                                        <address>
                                          ".Yii::$app->params['direccion']." 
                                          ".Yii::$app->params['poblacion']."<br>
                                          Email: ".Yii::$app->params['email'].". 
                                          Teléfono: ".Yii::$app->params['telefono'].". &nbsp;&nbsp;
                                          Movíl: ".Yii::$app->params['movil']."&nbsp;&nbsp;
                                        </address>
                                      </td>
                                    </tr>
                                    <tr>
                                     <th align='center' style='padding-top: 15px'><h4><address>ALBARÀ - CONTROL DE LLIURAMENT DE CLAUS</address></h4></th>
                                   </tr>
                                  </table>
                                </div>
                              </div>
                            </div>
                            <div class='row'>
                                <table class=\"table\">
                                  <tr>
                                    <td style='text-align: right; width: 25%;font-weight: bold'>DATA LLIURAMENT:</td>
                                    <td style='text-align: left; width: 25%'>".util::getDateTimeFormatedSqlToUser($objRegistro->getFechaRegistro())."</td>
                                    <td style='text-align: right; width: 25%;font-weight: bold'>No. OPERACIÓ:</td>
                                    <td style='text-align: left; width: 25%'>".str_pad($objRegistro->id, 6, "0", STR_PAD_LEFT)."</td>
                                  </tr>
                                </table>
                            </div>   
                            <div class='row'>
                                <div class='table-responsive'>
                                    <table class='table'>
                                    <tbody><tr>
                                        <th style='width:50%;text-align: center'>ENTREGAT PER</th>
                                        <th style='width:50%;text-align: center'>ENTREGAT A </th>
                                    </tr><tr>
                                        <td style='width:50%'>".$objRegistro->user->userInfo->nombres." ".$objRegistro->user->userInfo->apellidos."</td>
                                        <td style='width:50%;text-align: left'>$strDivResponsableText</td>
                                    </tr></tbody>
                                    </table>
                                </div>
                            </div>    
                            <!-- /.row -->";
        $strHtmlBody = "<div class=\"row\">
                          <div class=\"col-12 table-responsive\">
                            <table class=\"table table-striped small\">
                              <thead>
                              <tr>
                                <th style=\"width:9% \">Acción</th>
                                <th style=\"width:10%\">Código</th>
                                <th style=\"width:31%\">Descripción</th>
                                <th style=\"width:25%\">Cliente</th>
                                <th style=\"width:25%\">Propietario</th>
                              </tr>
                              </thead>
                              <tbody>
                              ".$addHtmlRows."
                              </tbody>
                            </table>
                          </div>
                          <!-- /.col -->
                        </div>";

        $strHtmlFooter = "<div class=\"row\">
                          <div class=\"col-12\">
                            <p style=\"margin-top: 10px; text-align: center \">
                              INCIDÈNCIES I COMENTARIS
                            </p>
                          </div>
                          <!-- -->
                          <div class=\"col-12\">
                            <p class=\"text-muted well well-sm shadow-none small\" style=\"margin-top: 10px;\">
                              ".$objRegistro->observacion."
                            </p>
                          </div>
                          <div class='row'>
                            <div class='table-responsive'>
                                <table class='table'>
                                <tbody><tr>
                                    <th  style='width:50%;text-align: center'>FIRMA LLIURADOR</th>
                                    <th style='width:50%;text-align: center'>FIRMA RECEPTOR</th>
                                </tr><tr>
                                    <td style='width:50%'>x.</td>
                                    <td style='width:50%'>x.".$strFirma."</td>
                                </tr></tbody>
                                </table>
                            </div>
                          </div>
                          <!-- -->
                          <div class=\"col-12\">
                            <p class=\"text-muted well well-sm shadow-none small\" style=\"margin-top: 10px; text-align: justify \">
                              Les claus s'han de retornar a SALVADÓ i GUBERT, a la mateixa oficina on s'han lliurat i en un termini de 24 hores des del lliurament. En cas contrari, el receptor ha d'informar de la causa del retard i la data estimada de retorn.
                            </p>
                          </div>  
                        </div>";

        $strHtml = "<div class=\"wrapper\">
                      <!-- Main content -->
                      <section class=\"invoice\">
                        ".$strHtmlHeader."
                        <!-- Table row -->
                        ".$strHtmlBody."
                        <!-- /.row -->
                        ".$strHtmlFooter."
                        <!-- /.row -->
                      </section>
                      <!-- /.content -->
                    </div>
                  <!-- ./wrapper -->";
        return $strHtml;
    }
}
