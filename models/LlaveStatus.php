<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "llavestatus".
 *
 * @property int $id
 * @property int|null $id_llave
 * @property int|null $id_registro
 * @property string|null $status Entrada/Salida
 * @property string|null $fecha
 *
 * @property Llave $llave
 */
class LlaveStatus extends \yii\db\ActiveRecord
{
    public $codigo = null;
    public $username = null;
    public $clientes = null;//cliente
    public $propietarios = null;
    public $comercial = null;
    public $nombre_propietario = null;
    public $llaves = null;
    public $fecha_registro = null;
    public $firma_soporte = null;
    public $descripcion_llave = null;
    public $llaves_e = null;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llave_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_llave','id_registro','status'], 'required'],
            [['id_llave','id_registro'], 'integer'],
            [['fecha'], 'safe'],
            [['status'], 'string', 'max' => 1],
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
            'id_llave' => 'Id Llave',
            'status' => 'Entrada/Salida',
            'fecha' => 'Fecha',
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
     * Gets query for [[Registro]].
     *
     * @return \yii\db\ActiveQuery|LlaveQuery
     */
    public function getRegistro()
    {
        return $this->hasOne(Registro::className(), ['id' => 'id_registro']);
    }

    /**
     * {@inheritdoc}
     * @return LlaveStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LlaveStatusQuery(get_called_class());
    }

}
