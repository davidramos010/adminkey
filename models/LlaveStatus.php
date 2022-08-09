<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "llavestatus".
 *
 * @property int $id
 * @property int|null $id_llave
 * @property string|null $status Entrasa/Salida
 * @property string|null $fecha
 *
 * @property Llave $llave
 */
class LlaveStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llavestatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_llave'], 'integer'],
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
            'status' => 'Entrasa/Salida',
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
     * {@inheritdoc}
     * @return LlaveStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LlaveStatusQuery(get_called_class());
    }
}
