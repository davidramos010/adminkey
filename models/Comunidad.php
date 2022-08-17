<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comunidad".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $direccion
 * @property string|null $telefono1
 * @property string|null $telefono2
 * @property string|null $contacto
 * @property string|null $nomenclatura
 *
 * @property Llave[] $llaves
 */
class Comunidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comunidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'direccion'], 'string', 'max' => 255],
            [['telefono1', 'telefono2', 'contacto'], 'string', 'max' => 100],
            [['nomenclatura'], 'string', 'max' => 6],
            [['nomenclatura'], 'unique'],
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
            'direccion' => 'Direccion',
            'telefono1' => 'Telefono1',
            'telefono2' => 'Telefono2',
            'contacto' => 'Contacto',
            'nomenclatura' => 'Nomenclatura',
        ];
    }

    /**
     * Gets query for [[Llaves]].
     *
     * @return \yii\db\ActiveQuery|LlaveQuery
     */
    public function getLlaves()
    {
        return $this->hasMany(Llave::className(), ['id_comunidad' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ComunidadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComunidadQuery(get_called_class());
    }

    public function getNext() {
        $next = $this->find()->where(['>', 'id', 0])->orderBy('id desc')->one();
        return (int)$next->id + 1;
    }
}
