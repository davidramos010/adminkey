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
 * @property string|null $observacion
 * @property string|null $deleted
 *
 * @property Contratos $contrato
 * @property User $usuario
 */
class ContratosLog extends \yii\db\ActiveRecord
{
    public $estado = null;
    public $nombre = null;
    public $cliente = null;
    public $propietario = null;
    public $llaves = null;

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
            [['llaves'], 'safe'],
            [['id_contrato'], 'required'],
            [['id', 'id_contrato', 'id_usuario'], 'integer'],
            [['fecha','deleted'], 'safe'],
            [['observacion','parametros', 'copia_firma'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_usuario' => 'id']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id']],
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
            'observacion' => Yii::t('app', 'Observación'),
        ];
    }

    /**
     * Gets query for [[Contrato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id' => 'id_contrato']);
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

    /**
     * Actualizar el campo delete
     * @param $numIdContratoLog
     * @return bool
     */
    public static function setDeleteContratoLog($numIdContratoLog): bool
    {
        $objContrato = self::findOne($numIdContratoLog);
        $objContrato->deleted = date('Y-m-d H:i:s');
        return $objContrato->save();
    }
}
