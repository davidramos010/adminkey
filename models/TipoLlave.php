<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo-llave".
 *
 * @property int $id
 * @property string|null $codigo
 * @property string|null $descripcion
 *
 * @property Llave[] $llaves
 */
class TipoLlave extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_llave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo'], 'string', 'max' => 2],
            [['descripcion'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[Llaves]].
     *
     * @return \yii\db\ActiveQuery|LlaveQuery
     */
    public function getLlaves()
    {
        return $this->hasMany(Llave::className(), ['id_tipo' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TipoLlaveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TipoLlaveQuery(get_called_class());
    }
}
