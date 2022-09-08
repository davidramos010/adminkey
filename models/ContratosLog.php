<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contratos_log".
 *
 * @property int $id
 * @property int|null $id_contrato
 * @property string|null $parametros relacion de id's de llaves
 * @property string|null $fecha
 * @property int|null $id_usuario
 * @property string|null $copia_firma
 *
 * @property Contrato $contrato
 * @property User $usuario
 */
class ContratosLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contratos_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'id_contrato', 'id_usuario'], 'integer'],
            [['fecha'], 'safe'],
            [['parametros', 'copia_firma'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_usuario' => 'id']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_contrato' => Yii::t('app', 'Id Contrato'),
            'parametros' => Yii::t('app', 'relacion de id\'s de llaves'),
            'fecha' => Yii::t('app', 'Fecha'),
            'id_usuario' => Yii::t('app', 'Id Usuario'),
            'copia_firma' => Yii::t('app', 'Copia Firma'),
        ];
    }

    /**
     * Gets query for [[Contrato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'id_usuario']);
    }
}
