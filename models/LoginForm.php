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
            [['authkey'], 'integer','message'=>'Codigo no validado.'],
            [['perfil'], 'integer'],
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

        if(!is_numeric($this->authkey) && !self::getAuthKey()){
            $this->addError($attribute, 'El codigo de acceso no es valido');
            Yii::$app->session->setFlash('error', 'El codigo de acceso no es valido');
        }
    }

    /**
     * Perfiles 1: user simple, 2: administrado
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if(!empty($this->authkey) && (int) $this->perfil==2)
        {
            $this->username = null;
            $this->password = null;
            $this->getAuthKey();
        }else{
            if(!empty($this->password)){
                $this->password = util::hash($this->password);
            }
        }

        if ($this->validate() && !empty($this->getUser())) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @return bool
     */
    public function getAuthKey()
    {
        if (!empty($this->authkey)) {
            $objUser = User::find()->where(['authkey'=> $this->authkey ])->one();

            if(!empty($objUser)){
                $objUserPerfil = PerfilesUsuario::find()->where(['id_user'=>$objUser->id,'id_perfil'=>2])->one();
                if(!empty($objUserPerfil)){
                    $this->_user = false;
                    $this->username = (!empty($objUser))?$objUser->username:null;
                    $this->password = (!empty($objUser))?$objUser->password:null;
                }
            }
        }

        return (!empty($objUser));
    }
}
