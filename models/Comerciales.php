<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comerciales".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $telefono
 * @property string|null $movil
 * @property string|null $contacto
 * @property string|null $cod_postal
 * @property string|null $poblacion
 * @property string|null $direccion
 * @property int $id_tipo_documento
 * @property string|null $documento
 * @property string|null $email
 * @property string|null $observacion
 * @property int $estado
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
            [['id_tipo_documento','estado'], 'integer'],
            [['nombre','direccion'], 'required', 'message'=> Yii::t('yii',  '{attribute} es requerido')],
            [['direccion','nombre','direccion','poblacion','email','observacion'], 'string', 'max' => 255],
            [['documento'], 'string', 'max' => 255],
            [['telefono', 'movil', 'contacto'], 'string', 'max' => 100],
            [['cod_postal'], 'string', 'max' => 6],
            [['email'], 'email','message'=> Yii::t('yii',  '{attribute} no es valido')],
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
            'telefono' => 'Teléfono',
            'movil' => 'Movil',
            'contacto' => 'Contacto',
            'direccion' => 'Dirección',
            'identificacion' => 'Identificación',
            'estado' => 'Estado',
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


    /**
     *  @param string $insert Si este método llamó al insertar un registro. Si false, significa que se llama al método mientras se actualiza un registro.
     * @return bool Si la inserción o actualización debe continuar. Si false, se cancelará la inserción o actualización.
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->nombre = trim(strtoupper($this->nombre));
        $this->direccion = trim(strtoupper($this->direccion));
        $this->contacto = !empty($this->contacto) ? trim(strtoupper($this->contacto)) : null;

        return true;
    }

    /**
     * Lista de comunidades activas
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getComercialesDropdownList()
    {
        $query = "SELECT id, nombre FROM comerciales WHERE estado=1 order by nombre";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre');
    }

}
