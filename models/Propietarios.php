<?php

namespace app\models;

use app\components\Tools;
use Yii;

/**
 * This is the model class for table "propietarios".
 *
 * @property int $id
 * @property string|null $nombre_propietario
 * @property int|null $tipo_documento_propietario 1:NIF,2:NIE,3:PASS,4:OTROS
 * @property string|null $documento_propietario
 * @property string|null $nombre_representante
 * @property int|null $tipo_documento_representante 1:NIF,2:NIE,3:PASS,4:OTROS
 * @property string|null $documento_representante
 * @property string|null $direccion
 * @property string|null $cod_postal
 * @property string|null $poblacion
 * @property string|null $telefono
 * @property string|null $movil
 * @property string|null $email
 * @property string|null $observaciones
 */
class Propietarios extends \yii\db\ActiveRecord
{
    const arrTipoDocumentos = [1=>'NIF',2=>'NIE',3=>'PASS',4=>'OTROS'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'propietarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento_propietario', 'tipo_documento_representante'], 'integer'],
            [[ 'telefono', 'movil', 'observaciones'], 'string', 'max' => 100],
            [[ 'documento_propietario', 'documento_representante'], 'string', 'max' => 50],
            [['nombre_propietario','nombre_representante', 'direccion', 'poblacion', 'email'], 'string', 'max' => 250],
            [['cod_postal'], 'string', 'max' => 10],
            ['email', 'email'],
            [['cod_postal','direccion','movil'], 'required','message'=> Yii::t('yii',  '{attribute} es requerido.') ],
            ['documento_propietario', 'validateDocumentos'],
            [
                ['documento_propietario'],
                function ($attribute) {
                    if (empty($this[$attribute]) && empty($this['tipo_documento_propietario']) ) {
                        $this->addError($attribute, "Tipo documento no valido!");
                    }
                },
            ],
            [
                ['documento_representante'],
                function ($attribute) {
                    if (empty($this[$attribute]) && empty($this['tipo_documento_propietario']) ) {
                        $this->addError($attribute, "Tipo documento no valido!");
                    }
                },
            ],
            [
                ['nombre_propietario','nombre_representante'],
                function ($attribute) {
                    if (empty($this['nombre_propietario']) && empty($this['nombre_representante']) ) {
                        $this->addError($attribute, "Debe ingresar un Propietario/Representante");
                    }
                },
            ],
            [
                ['telefono', 'movil'],
                function ($attribute) {
                    if (empty($this['telefono']) && empty($this['movil']) ) {
                        $this->addError($attribute, "Debe ingresar un teléfono/movil");
                    }
                },
            ],

        ];
    }

    public function validateDocumentos($attribute, $params)
    {
        if (!$this->hasErrors() && empty($this[$attribute]) && empty($this['tipo_documento_propietario']) ) {
            $this->addError($attribute, "Tipo documento no valido!");
            Yii::$app->session->setFlash('error', 'usuario o password incorrectos.');
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre_propietario' => Yii::t('app', 'Nombre Propietario'),
            'tipo_documento_propietario' => Yii::t('app', '1:NIF,2:NIE,3:PASS,4:OTROS'),
            'documento_propietario' => Yii::t('app', 'Documento Propietario'),
            'nombre_representante' => Yii::t('app', 'Nombre Representante'),
            'tipo_documento_representante' => Yii::t('app', '1:NIF,2:NIE,3:PASS,4:OTROS'),
            'documento_representante' => Yii::t('app', 'Documento Representante'),
            'direccion' => Yii::t('app', 'Direccion'),
            'cod_postal' => Yii::t('app', 'Cod Postal'),
            'poblacion' => Yii::t('app', 'Poblacion'),
            'telefono' => Yii::t('app', 'Telefono'),
            'movil' => Yii::t('app', 'Movil'),
            'email' => Yii::t('app', 'Email'),
            'observaciones' => Yii::t('app', 'Observaciones'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PropietariosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PropietariosQuery(get_called_class());
    }

    /**
     * @param int $numTipoDoc
     * @return string
     */
    public static function getTipoDocmento(int $numTipoDoc):string
    {
        return self::arrTipoDocumentos[$numTipoDoc];
    }

    /**
     * @return string
     */
    public function getNombre(){
        $strNombrePropietario = (!empty($this->nombre_propietario))?strtoupper($this->nombre_propietario):'';
        $strNombrePropietario = (empty($strNombrePropietario))?strtoupper($this->nombre_representante):$strNombrePropietario;
        return trim($strNombrePropietario);
    }
}
