<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $authkey;
    public $rememberMe = true;
    public $perfil = 2;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'string', 'max' => 255],
            [['perfil'], 'integer'],
            [
                ['username', 'password'],
                'required',
                'when' => function () {
                    return $this->perfil==1;
                },
                'whenClient' => "function (attribute, value) {
                 var validate = false; 
                 if(
                    ($('#loginform-perfil').val()=='1' || $('#loginform-perfil').val()==1) &&
                     ($('#loginform-username').val()=='' ||
                     $('#loginform-password').val()=='')
                   )
                   {
                     validate = true;
                   }
                   return validate;
                }"
            ],
            [
                ['authkey'],
                'required',
                'when' => function () {
                    return $this->perfil==2;
                },
                'whenClient' => "function (attribute, value) {
                 var validate = false; 
                 if(
                    ($('#loginform-perfil').val()=='2' || $('#loginform-perfil').val()==2) &&
                     $('#authkey').val()==''
                   )
                   {
                     validate = true;
                   }
                   return validate;
                }"
            ],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['authkey', 'validateAuthkey'],
        ];
    }



    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->username) && !empty($this->password) ) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'usuario o password incorrectos.');
                Yii::$app->session->setFlash('error', 'usuario o password incorrectos.');
            }
        }
    }

    public function validateAuthkey($attribute, $params)
    {
        if(empty($this->authkey)){
           return true;
        }

        if(!is_numeric($this->authkey) || !self::getAuthKey()){
            $strMessaje = 'El código de acceso no es valido o el usuario esta inactivo';
            $this->addError($attribute, $strMessaje);
            Yii::$app->session->setFlash('error', $strMessaje);
        }
    }

    /**
     * Perfiles 2: user gestor, 1: administrado
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if (!empty($this->authkey) && (int)$this->perfil == 2) {
            $this->username = null;
            $this->password = null;
            $this->getAuthKey();
        } else {
            $this->authkey = null;
            if (!empty($this->password)) {
                $this->password = util::hash($this->password);
            }
        }

        if ($this->validate() && !empty($this->getUser())) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }else{
            $strMessaje = 'El código de acceso no es valido o el usuario no esta activo';
            Yii::$app->session->setFlash('error', $strMessaje);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     * Validar estado del usuario
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
            if(isset($this->_user->userInfo) && $this->_user->userInfo->estado == 0){
                $this->_user = false;
            }
        }
        return $this->_user;
    }

    /**
     * @return bool
     */
    public function getAuthKey()
    {
        $objUserPerfil = null;
        if (!empty($this->authkey)) {
            $objUser = User::find()->where(['authkey'=> $this->authkey ])->one();
            if(!empty($objUser)){
                $objUserPerfil = PerfilesUsuario::find()->where(['id_user'=>$objUser->id])->andWhere(['in','id_perfil',[2,3]])->one();
                $objUserInfo = UserInfo::find()->where(['id_user'=>$objUser->id,'estado'=>1])->one();
                if(!empty($objUserPerfil) && !empty($objUserInfo)){
                    $this->_user = false;
                    $this->username = (!empty($objUser))?$objUser->username:null;
                    $this->password = (!empty($objUser))?$objUser->password:null;
                }
            }
        }

        return (!empty($objUserPerfil));
    }
}
