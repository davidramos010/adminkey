<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
 *
 * @property Llave $llave
 * @property User $user
 */
class Registro extends \yii\db\ActiveRecord
{
    public $codigo = null;
    public $username = null;
    public $comunidad = null;
    public $comercial = null;
    public $nombre_propietario = null;
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
            [['id_user', 'id_llave', 'id_comercial', 'id_comercial'], 'integer'],
            [['entrada', 'salida'], 'safe'],
            [['observacion','codigo','username','comunidad','comercial','nombre_propietario'], 'string', 'max' => 255],
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
     * @return \yii\db\ActiveQuery
     */
    public function getComercial()
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
}
