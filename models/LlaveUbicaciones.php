<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "llave_ubicaciones".
 *
 * @property int $id
 * @property string $tipo_almacen Despacho,Armario,Cajon
 * @property string $descripcion_almacen
 * @property string $dirección
 * @property string|null $observaciones
 */
class LlaveUbicaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llave_ubicaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_almacen', 'descripcion_almacen', 'dirección'], 'required'],
            [['tipo_almacen'], 'string', 'max' => 100],
            [['descripcion_almacen', 'dirección', 'observaciones'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tipo_almacen' => Yii::t('app', 'Despacho,Armario,Cajon'),
            'descripcion_almacen' => Yii::t('app', 'Descripcion Almacen'),
            'dirección' => Yii::t('app', 'Dirección'),
            'observaciones' => Yii::t('app', 'Observaciones'),
        ];
    }
}
