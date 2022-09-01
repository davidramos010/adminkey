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
     *  @param string $insert Si este método llamó al insertar un registro. Si false, significa que se llama al método mientras se actualiza un registro.
     * @return bool Si la inserción o actualización debe continuar. Si false, se cancelará la inserción o actualización.
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->nombre = trim(strtoupper($this->nombre));// Nombre en mayusculas
        $this->contacto = trim(strtoupper($this->contacto));// contacto en mayusculas
        return true;
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
