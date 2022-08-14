<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comerciales".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $telefono
 * @property string|null $contacto
 */
class Comerciales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comerciales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 255],
            [['telefono', 'contacto'], 'string', 'max' => 100],
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
            'telefono' => 'Telefono',
            'contacto' => 'Contacto',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ComercialesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComercialesQuery(get_called_class());
    }
}
