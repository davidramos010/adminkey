<?php

namespace app\models;

use Mpdf\Tag\U;
use phpDocumentor\Reflection\Types\This;
use yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property int $idPerfil
 * @property string $password_new
 * @property string $authKey_new
 * @property Perfiles $perfiles
 * @property PerfilesUsuario $perfilesUsuario
 */

class User extends ActiveRecord implements IdentityInterface
{
    public $idPerfil = null;
    public $password_new = null;
    public $authKey_new = null;

    public $nombres = null;
    public $apellidos = null;
    public $telefono = null;
    public $perfil = null;
    public $estado = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('User', 'Id'),
            'username' => Yii::t('User', 'Usuario'),
            'name' => Yii::t('User', 'Nombre'),
            'password' => Yii::t('User', 'Password'),
            'authKey' => Yii::t('User', 'AuthKey'),
            'accessToken' => Yii::t('User', 'AccessToken'),
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password', 'authKey'], 'required', 'message'=> Yii::t('yii',  '{attribute} no es valido')],
            [['name','username', 'password', 'authKey', 'accessToken','password_new','authKey_new'], 'string', 'max' => 255],
            [['password_new'], 'string', 'min' => 6, 'max' => 255,'message' => 'Debe tener más de 6 caracteres.'],
            [['authKey_new'], 'number', 'min' => 99999, 'max' => 9999999, 'message' => 'Debe tener entre 6 y 7 números.'],
            [['authKey','password'], 'required', 'when' => function($model) {
                return $model->isNewRecord;
            },'whenClient' => "function (attribute, value) {
                if($('#password').val()=='')
                  $('#password').val($('#password_new').val());
                  
                if($('#authKey').val()=='')
                  $('#authKey').val($('#authKey_new').val());  
                 
                return ($('#password').val() != '' && $('#authKey').val() != '');
            }"],
            [['idPerfil'], 'integer'],
            [['username'], 'unique'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     *  @param string $insert Si este método llamó al insertar un registro. Si false, significa que se llama al método mientras se actualiza un registro.
     * @return bool Si la inserción o actualización debe continuar. Si false, se cancelará la inserción o actualización.
     */
    public function beforeSave($insert)
    {
        $objFindAuthKey = null;
        if (!parent::beforeSave($insert)) {
            return false;
        }
        // Validar edición del password
        if(!empty($this->password_new)){
            $this->password = util::hash($this->password_new);
        }

        // Validar que el authKey es unico para los gestores
        if(!empty($this->authKey_new)){
            if($this->isNewRecord)
               $objFindAuthKey = User::find()->where(['authKey'=>$this->authKey_new])->one();

            if(!$this->isNewRecord)
               $objFindAuthKey = User::find()->where(['authKey'=>$this->authKey_new])->andWhere(['<>','id',$this->id]) ->one();

            if(!empty($objFindAuthKey)){
                $this->addError('authKey', 'El authKey ingresado no es valido.');
                return false;
            }
        }
        return true;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getPerfiluser()
    {
        return $this->hasOne(PerfilesUsuario::className(), ['id_user' => 'id']);
    }


    /**
     * Gets query for [[User_Info]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['id_user' => 'id']);
    }

    /**
     * @return array
     * @throws yii\db\Exception
     */
    public static function getPerfilesDropdownList()
    {
        $query = "SELECT id, UPPER(nombre) as nombre FROM perfiles order by nombre ASC";
        $result = Yii::$app->db
            ->createCommand($query)
            ->queryAll();
        return ArrayHelper::map($result, 'id', 'nombre');
    }

    /**
     * Registrar usuario en el sistema
     * @param array $arrParams
     * @return array
     */
    public function setUser( array $arrParams ):array
    {
        $modelInfo = new UserInfo();
        $model = new This();
        $strErrores = '';
        //limpiar texto y poner en mayusculas
        $arrParams['UserInfo']['nombres'] = Util::getStringFormatUpper($arrParams['UserInfo']['nombres']);
        $arrParams['UserInfo']['apellidos'] = Util::getStringFormatUpper($arrParams['UserInfo']['apellidos']);
        $arrParams['UserInfo']['direccion'] = Util::getStringFormatUpper($arrParams['UserInfo']['direccion']);
        $arrParams['UserInfo']['codigo'] = Util::getStringFormatUpper($arrParams['UserInfo']['codigo']);

        $arrUser['User'] = $arrParams['User'];
        $arrUser['User']['name'] = trim($arrParams['UserInfo']['nombres']. ' ' .$arrParams['UserInfo']['apellidos']);
        $arrUser['User']['password'] = trim($arrUser['User']['password_new']);
        $arrUser['User']['authKey'] = trim($arrUser['User']['authKey_new']);
        $transaction = Yii::$app->db->beginTransaction();
        $bolInserUser = ($model->load($arrUser) && $model->save());
        $bolInserPerfil = false;
        // pintar errores
        if(!$bolInserUser){
            $arrError = $model->getErrors();
            foreach ($arrError as $item){
                if(isset($item[0])){
                    $strErrores .= '<br>-'.trim($item[0]);
                }
            }
        }

        $arrUserInfo['UserInfo'] = $arrParams['UserInfo'];
        $arrUserInfo['UserInfo']['id_user'] = $model->id;
        $bolInserUserInfo = ($bolInserUser && $modelInfo->load($arrUserInfo) && $modelInfo->save());

        // pintar errores
        if($bolInserUser && !$bolInserUserInfo){
            $arrError = $modelInfo->getErrors();
            foreach ($arrError as $item){
                if(isset($item[0])){
                    $strErrores .= '<br>-'.trim($item[0]);
                }
            }
        }

        if($bolInserUser && $bolInserUserInfo){
            $newPerfilUser = new PerfilesUsuario();
            $newPerfilUser->id_user = (int) $model->id;
            $newPerfilUser->id_perfil = (int) $model->idPerfil;
            $bolInserPerfil = $newPerfilUser->save();
            if(!$bolInserPerfil){
                $strErrores .= '<br>-El perfil no se asigna correctamente';
            }
        }

        if($bolInserPerfil && $bolInserUser && $bolInserUserInfo){
            if(!empty($transaction->commit())){
                $strErrores .= '<br>-No se puede registrar. Valide los datos he intente nuevamente.';
            }
        }

        if(!empty($strErrores)){
            $transaction->rollBack();
            $strErrores .= '<br>-Error al guardar. Valide los datos y vuelva a intentar.';
        }

        return ['ok'=>empty($strErrores),'message'=> empty($strErrores)?'OK':$strErrores ];
    }
}
