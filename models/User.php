<?php

namespace app\models;

use Mpdf\Tag\U;
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

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
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
            [['password_new'], 'string', 'min' => 6, 'max' => 255,'message' => 'Debe tener mas de 6 caracteres.'],
            [['authKey_new'], 'number', 'min' => 99999, 'max' => 9999999, 'message' => 'Debe tener entre 6 y 7 números.'],
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
                //Yii::$app->session->setFlash('error', 'El authKey ingresado no es valido.');
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
}
