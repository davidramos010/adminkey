<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contratos_log_llave".
 *
 * @property int $id
 * @property int|null $id_llave
 * @property int|null $id_contrato_log
 *
 * @property ContratosLog $contratoLog
 * @property Llave $llave
 */
class ContratosLogLlave extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contratos_log_llave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_llave', 'id_contrato_log'], 'integer'],
            [['id_contrato_log'], 'exist', 'skipOnError' => true, 'targetClass' => ContratosLog::class, 'targetAttribute' => ['id_contrato_log' => 'id']],
            [['id_llave'], 'exist', 'skipOnError' => true, 'targetClass' => Llave::class, 'targetAttribute' => ['id_llave' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_llave' => Yii::t('app', 'Id Llave'),
            'id_contrato_log' => Yii::t('app', 'Id Contrato Log'),
        ];
    }

    /**
     * Gets query for [[ContratoLog]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContratoLog()
    {
        return $this->hasOne(ContratosLog::class, ['id' => 'id_contrato_log']);
    }

    /**
     * Gets query for [[Llave]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLlave()
    {
        return $this->hasOne(Llave::class, ['id' => 'id_llave']);
    }
}
