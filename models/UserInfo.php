<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User_info".
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $nombres
 * @property string|null $apellidos
 * @property string|null $telefono
 * @property string|null $direccion
 * @property string|null $email
 * @property string|null $codigo
 * @property string|null $id_comercial
 * @property int|null $estado 1: Activo: 0:Inactivo
 * @property int $tipo_documento
 * @property string $documento
 * @property string|null $created
 *
 * @property User $user
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'estado', 'id_comercial','tipo_documento'], 'integer'],
            [['created'], 'safe'],
            [['nombres', 'apellidos', 'direccion'], 'string', 'max' => 255],
            [['email'], 'email', 'message'=> Yii::t('yii',  '{attribute} no es valido')],
            [['tipo_documento','nombres', 'apellidos'], 'required', 'message'=> Yii::t('yii',  '{attribute} no es valido')],
            [['telefono'], 'string', 'max' => 30],
            [['documento'], 'string', 'max' => 20],
            [['codigo'], 'string', 'max' => 100],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_comercial'], 'exist', 'skipOnError' => true, 'targetClass' => Comerciales::className(), 'targetAttribute' => ['id_comercial' => 'id']],
            [['nombres'], 'required', 'when' => function($model) {
                return  $model->isNewRecord;
            }],
            [['documento'], 'required', 'when' => function($model) {
                return in_array($model->tipo_documento, [ util::arrTipoDocumentos[1],util::arrTipoDocumentos[2],util::arrTipoDocumentos[3] ]) ;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_user' => Yii::t('app', 'Id User'),
            'nombres' => Yii::t('app', 'Nombres'),
            'apellidos' => Yii::t('app', 'Apellidos'),
            'telefono' => Yii::t('app', 'Telefono'),
            'direccion' => Yii::t('app', 'Direccion'),
            'email' => Yii::t('app', 'Email'),
            'codigo' => Yii::t('app', 'Codigo'),
            'estado' => Yii::t('app', '1: Activo: 0:Inactivo'),
            'created' => Yii::t('app', 'Created'),
        ];
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
     * @return UserInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserInfoQuery(get_called_class());
    }
}
