<?php

namespace app\models;

use app\components\Tools;
use Yii;


/**
 * This is the model class for table "llave_notas".
 *
 * @property int $id
 * @property int $id_llave
 * @property int $id_user
 * @property int $delete
 * @property string $nota
 * @property string $created
 *
 * @property Llave $llave
 * @property User $user
 */
class LlaveNotas extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'llave_notas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_llave', 'id_user'], 'required', 'message'=> Yii::t('yii',  'Es requerido')],
            [['id_llave', 'id_user'], 'integer'],
            [['nota'], 'string', 'max' => 350],
            [['created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_llave' => Yii::t('app','Llave'),
            'id_user' => Yii::t('app','User'),
            'nota' => Yii::t('app','Nota'),
            'created' => Yii::t('app','Date')
        ];
    }

    /**
     * Gets query for [[comunidad]].
     *
     * @return \yii\db\ActiveQuery|ComunidadQuery
     */
    public function getLlave()
    {
        return $this->hasOne(Llave::className(), ['id' => 'id_llave']);
    }

    /**
     * Gets query for [[LlaveStatuses]].
     *
     * @return \yii\db\ActiveQuery|LlaveStatusQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @param int $numIdLlave
     * @return array
     */
    public function getHistory(int $numIdLlave):array
    {
        return $this->find()->alias('h')->leftJoin('user us',' us.id = h.id_user ')
            ->where(['id_llave'=>$numIdLlave])->orderBy('created DESC')->all();
    }

    /**
     * @param int $numIdLlave
     * @param string $strNota
     * @return bool
     */
    public function setNewNota( int $numIdLlave, string $strNota):bool
    {
        $bolReturn = true;
        if(!empty($numIdLlave) && !empty($strNota)){
            $newObjNota = new LlaveNotas();
            $newObjNota->id_llave = $numIdLlave;
            $newObjNota->id_user = Yii::$app->user->identity->id;
            $newObjNota->nota = $strNota;
            $bolReturn = $newObjNota->save();
        }

        return $bolReturn;
    }

    /**
     * @param int $numIdLlave
     * @param string $strNota
     * @return bool
     */
    public function setNewNotaAjax( int $numIdLlave, string $strNota):array
    {
        $bolReturn = true;
        $arrReturn = [];
        if(!empty($numIdLlave) && !empty($strNota)){
            $newObjNota = new LlaveNotas();
            $newObjNota->id_llave = $numIdLlave;
            $newObjNota->id_user = Yii::$app->user->identity->id;
            $newObjNota->nota = $strNota;
            $bolReturn = $newObjNota->save();
            if($bolReturn){
                $arrReturn = ['error'=> !$bolReturn ? 'No se pudo crear la nota':'' , 'ok_sms'=>'Se crea correctamente','nota'=> addslashes($strNota) ,'fecha'=> date('d/m/Y h:i:s') ,'usuario'=> $newObjNota->user->name,'id'=>$newObjNota->id ];
            }
        }

        if(!$bolReturn){
            $arrReturn = ['error'=> 'No se puede crear la nota. Comunicar al administrador.'];
        }

        return $arrReturn;
    }




}
