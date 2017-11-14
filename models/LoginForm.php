<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\behaviors\DefaultUserBehavior;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $rememberMe = true;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DefaultUserBehavior::className(),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $userModel = new User();
            return Yii::$app->user->login($userModel->getOrCreateUser($this->username), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }
}
