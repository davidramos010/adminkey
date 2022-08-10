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
 * @property int|null $copia
 * @property string|null $codigo
 * @property string|null $descripcion
 * @property string|null $observacion
 * @property int|null $activa
 *
 * @property Comunidad $comunidad
 * @property LlaveStatus[] $llaveStatuses
 * @property Registro[] $registros
 * @property TipoLlave $tipo
 */
class Llave extends \yii\db\ActiveRecord
{
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
            [['id_comunidad', 'id_tipo', 'copia', 'activa'], 'integer'],
            [['codigo'], 'string', 'max' => 100],
            [['descripcion', 'observacion'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
            [['id_comunidad'], 'exist', 'skipOnError' => true, 'targetClass' => Comunidad::className(), 'targetAttribute' => ['id_comunidad' => 'id']],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoLlave::className(), 'targetAttribute' => ['id_tipo' => 'id']],
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
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
            'observacion' => 'Observacion',
            'activa' => 'Activa',
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

}
