<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comunidad".
 *
 * @property int $id
 * @property int $id_tipo_documento
 * @property string|null $documento
 * @property string|null $nombre
 * @property string|null $cod_postal
 * @property string|null $poblacion
 * @property string|null $direccion
 * @property string|null $telefono1
 * @property string|null $telefono2
 * @property string|null $contacto
 * @property string|null $nomenclatura
 * @property string|null $email
 * @property int $estado
 * @property string|null $created
 *
 * @property Llave[] $llaves
 */
class Comunidad extends \yii\db\ActiveRecord
{
    public $arrTipoDocumentos = [1=>'NIF',2=>'NIE',3=>'PASS',4=>'OTROS'];
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
            [['id_tipo_documento', 'estado'], 'integer'],
            [['created'], 'safe'],
            [['nombre', 'direccion','poblacion','email'], 'string', 'max' => 255],
            [['telefono1', 'telefono2', 'contacto','documento'], 'string', 'max' => 100],
            [['cod_postal'], 'string', 'max' => 10],
            [['nomenclatura'], 'string', 'max' => 6],
            [['nomenclatura'], 'unique'],
            [['email'], 'email','message'=> Yii::t('yii',  '{attribute} no es valido')],
            [['nombre','direccion'], 'required', 'message'=> Yii::t('yii',  '{attribute} es requerido')],
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
        $this->direccion = trim(strtoupper($this->direccion));// direccion en mayusculas
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
