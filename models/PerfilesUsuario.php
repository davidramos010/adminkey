<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perfiles_usuario".
 *
 * @property int $id
 * @property int $id_perfil
 * @property int $id_user
 *
 * @property Perfile $perfil
 * @property User $user
 */
class PerfilesUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfiles_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_perfil', 'id_user'], 'required'],
            [['id_perfil', 'id_user'], 'integer'],
            /*[['id_perfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfile::className(), 'targetAttribute' => ['id_perfil' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_perfil' => 'Id Perfil',
            'id_user' => 'Id User',
        ];
    }

    /**
     * Gets query for [[Perfil]].
     *
     * @return \yii\db\ActiveQuery|PerfileQuery
     */
    public function getPerfil()
    {
        return $this->hasOne(Perfiles::className(), ['id' => 'id_perfil']);
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
     * {@inheritdoc}
     * @return PerfilesUsuarioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PerfilesUsuarioQuery(get_called_class());
    }

    public static function getIndexPerfil(PerfilesUsuario $objParam, LoginForm $modelLogin):string
    {
        $strReturn = "";
        if(isset($objParam) && !empty($objParam->id_perfil) && !empty($modelLogin->perfil)){
            $strReturn = Yii::$app->params['index_perfil'][$modelLogin->perfil];
        }
        return $strReturn;
    }
}
