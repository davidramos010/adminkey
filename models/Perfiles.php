<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perfiles".
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 *
 * @property PerfilesUsuario[] $perfilesUsuarios
 */
class Perfiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'descripcion'], 'required'],
            [['nombre'], 'string', 'max' => 50],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[PerfilesUsuarios]].
     *
     * @return \yii\db\ActiveQuery|PerfilesUsuarioQuery
     */
    public function getPerfilesUsuarios()
    {
        return $this->hasMany(PerfilesUsuario::className(), ['id_perfil' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return PerfilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PerfilesQuery(get_called_class());
    }
}
