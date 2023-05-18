<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_log".
 *
 * @property int $id
 * @property int|null $id_registro
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
 * @property string|null $action
 * @property string|null $nombre_responsable
 * @property string|null $telefono
 */
class RegistroLog extends \yii\db\ActiveRecord
{
    public $codigo = null;
    public $username = null;
    public $clientes = null;//cliente
    public $propietarios = null;
    public $comercial = null;
    public $nombre_propietario = null;
    public $llaves = null;
    public $fecha_registro = null;
    public $status = null;


    private CONST ARR_SALIDAS = ['S','SALIDA'];
    private CONST ARR_ENTRADAS = ['E','ENTRADA'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user', 'id_registro', 'id_llave', 'id_comercial','tipo_documento'], 'integer'],
            [['entrada', 'salida','signature'], 'safe'],
            [['documento','telefono','action'], 'string', 'max' => 20],
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
     * @return string|null
     */
    public function getFechaRegistro(){
        $this->fecha_registro = (!empty($this->entrada))?$this->entrada:null;
        $this->fecha_registro = (empty($this->fecha_registro))?$this->salida:$this->fecha_registro;
        return $this->fecha_registro;
    }

}
