<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contratos".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property string|null $documento
 * @property int|null $estado
 * @property string|null $fecha_ini
 * @property string|null $fecha_fin
 * @property int|null $id_user
 * @property string|null $created
 *
 * @property ContratosLog[] $contratosLogs
 * @property User $usuario
 */
class Contratos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contratos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado', 'id_user'], 'integer'],
            [['fecha_ini', 'fecha_fin', 'created'], 'safe'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion','documento'], 'string', 'max' => 255],
            [['nombre','fecha_ini'], 'required','message'=> Yii::t('yii',  '{attribute} es requerido.') ],
            [['nombre'], 'unique','message'=> Yii::t('yii',  '{attribute} es un campo unico y ya existe.') ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'documento' => Yii::t('app', 'Documento'),
            'estado' => Yii::t('app', 'Estado'),
            'fecha_ini' => Yii::t('app', 'Fecha Ini'),
            'fecha_fin' => Yii::t('app', 'Fecha Fin'),
            'id_user' => Yii::t('app', 'Id User'),
            'created' => Yii::t('app', 'Created'),
        ];
    }

    /**
     * Gets query for [[ContratosLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContratosLogs()
    {
        return $this->hasMany(ContratosLog::className(), ['id_contrato' => 'id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
