<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codipostal".
 *
 * @property int $internalid
 * @property float $cp
 * @property string|null $carrer
 * @property string $poblacio
 * @property float $provinciaid
 * @property string|null $provincia
 * @property string $paisid
 * @property string|null $pais
 *
 * @property Pai $pais0
 * @property Provincium $provincia0
 */
class Codipostal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codipostal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['internalid', 'cp', 'poblacio', 'provinciaid', 'paisid'], 'required'],
            [['internalid'], 'integer'],
            [['cp', 'provinciaid'], 'number'],
            [['carrer', 'poblacio', 'provincia', 'pais'], 'string', 'max' => 100],
            [['paisid'], 'string', 'max' => 2],
            [['internalid'], 'unique'],
            [['paisid'], 'exist', 'skipOnError' => true, 'targetClass' => Pai::className(), 'targetAttribute' => ['paisid' => 'paisid']],
            [['provinciaid'], 'exist', 'skipOnError' => true, 'targetClass' => Provincium::className(), 'targetAttribute' => ['provinciaid' => 'provinciaid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'internalid' => Yii::t('app', 'Internalid'),
            'cp' => Yii::t('app', 'Cp'),
            'carrer' => Yii::t('app', 'Carrer'),
            'poblacio' => Yii::t('app', 'Poblacio'),
            'provinciaid' => Yii::t('app', 'Provinciaid'),
            'provincia' => Yii::t('app', 'Provincia'),
            'paisid' => Yii::t('app', 'Paisid'),
            'pais' => Yii::t('app', 'Pais'),
        ];
    }

    /**
     * Gets query for [[Pais0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais0()
    {
        return $this->hasOne(Pai::className(), ['paisid' => 'paisid']);
    }

    /**
     * Gets query for [[Provincia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvincia0()
    {
        return $this->hasOne(Provincium::className(), ['provinciaid' => 'provinciaid']);
    }
}
