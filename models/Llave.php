<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "llave".
 *
 * @property int $id
 * @property int|null $id_comunidad
 * @property int|null $id_tipo
 * @property int|null $id_propietario
 * @property int|null $id_ubicacion
 * @property int|null $copia
 * @property string|null $codigo
 * @property string|null $descripcion
 * @property string|null $observacion
 * @property int|null $activa
 * @property int|null $alarma
 * @property string|null $codigo_alarma
 * @property string|null $nomenclatura
 * @property int|null $facturable
 *
 * @property Comunidad $comunidad
 * @property LlaveStatus[] $llaveStatuses
 * @property Registro[] $registros
 * @property TipoLlave $tipo
 * @property Propietarios $propietarios
 * @property LlaveUbicaciones $llaveUbicaciones
 */
class Llave extends \yii\db\ActiveRecord
{

    public $llaveLastStatus = null;
    public $nombre_propietario = null;
    public $nomenclatura = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'id_llave_ubicacion', 'id_tipo','descripcion'], 'required', 'message'=> Yii::t('yii',  '{attribute} es requerido')],
            [['id_comunidad', 'id_tipo', 'id_propietario', 'id_llave_ubicacion','copia', 'activa','alarma','facturable'], 'integer'],
            [['codigo','nombre_propietario','nomenclatura'], 'string', 'max' => 100],
            [['descripcion', 'observacion','codigo_alarma'], 'string', 'max' => 255],
            /*[['codigo'], 'unique'],*/
            [['id_comunidad'], 'exist', 'skipOnError' => true, 'targetClass' => Comunidad::className(), 'targetAttribute' => ['id_comunidad' => 'id']],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoLlave::className(), 'targetAttribute' => ['id_tipo' => 'id']],
            [['id_propietario'], 'exist', 'skipOnError' => true, 'targetClass' => Propietarios::className(), 'targetAttribute' => ['id_propietario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_comunidad' => 'Id comunidad',
            'id_tipo' => 'Id Tipo',
            'copia' => 'Copia',
            'codigo' => 'Código',
            'descripcion' => 'Descripción',
            'observacion' => 'Observación',
            'activa' => 'Activa',
            'facturable' => 'Facturable',
            'alarma' => 'Alarma'
        ];
    }

    /**
     * Gets query for [[comunidad]].
     *
     * @return \yii\db\ActiveQuery|ComunidadQuery
     */
    public function getComunidad()
    {
        return $this->hasOne(Comunidad::className(), ['id' => 'id_comunidad']);
    }

    /**
     * Gets query for [[LlaveStatuses]].
     *
     * @return \yii\db\ActiveQuery|LlaveStatusQuery
     */
    public function getLlaveStatuses()
    {
        return $this->hasMany(LlaveStatus::className(), ['id_llave' => 'id']);
    }

    public function getLlaveLastStatus()
    {
        return $this->hasOne(LlaveStatus::className(), ['id_llave' => 'id'])->orderBy(['id'=>SORT_DESC]);
    }

    /**
     * Gets query for [[Registros]].
     *
     * @return \yii\db\ActiveQuery|RegistroQuery
     */
    public function getRegistros()
    {
        return $this->hasMany(Registro::className(), ['id_llave' => 'id']);
    }

    /**
     * Gets query for [[Tipo]].
     *
     * @return \yii\db\ActiveQuery|TipoLlaveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(TipoLlave::className(), ['id' => 'id_tipo']);
    }

    /**
     * Gets query for [[Propietario]].
     *
     * @return \yii\db\ActiveQuery|Propietarios
     */
    public function getPropietarios()
    {
        return $this->hasOne(Propietarios::className(), ['id' => 'id_propietario']);
    }

    /**
     * {@inheritdoc}
     * @return LlaveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LlaveQuery(get_called_class());
    }


    public static function getComunidadesDropdownList()
    {
        $query = "SELECT id, nombre FROM comunidad order by nombre";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre');
    }


    public static function getTipoLlaveDropdownList()
    {
        $query = "SELECT id, descripcion FROM tipo_llave order by descripcion";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'descripcion');
    }

    public static function getUbicacionDropdownList()
    {
        $query = "SELECT id, CONCAT(tipo_almacen, ' ', descripcion_almacen) as descripcion_almacen FROM llave_ubicaciones order by descripcion_almacen ASC";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'descripcion_almacen');
    }

    public static function getPropietariosDropdownList()
    {
        $query = "SELECT pp.id, (CASE
                                    WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                                    WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                                    ELSE NULL
                                END) as nombre_propietario 
                    FROM propietarios pp ORDER BY nombre_propietario ASC, nombre_representante ASC ";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre_propietario');
    }

    public function getNext() {
        $next = $this->find()->where(['id_comunidad' => $this->id_comunidad])->count();
        return (int)$next+1;
    }

}
