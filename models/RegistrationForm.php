<?php
/**
 * Created by PhpStorm.
 * User: sasha2567
 * Date: 04.08.16
 * Time: 13:06
 */

namespace app\models;

use Yii;
use yii\base\Model;



/**
 * RegistrationForm is the model behind the registration form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegistrationForm extends Model
{
    public $username;
    public $password;
    public $confirmpassword;
    public $email;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => 'Please choose a username.'],
            // email has to be a valid email address
            ['email', 'email', 'message' => 'Please enter another email'],
            // password is validated by validatePassword()
            [['password', 'confirmpassword'], 'validatePassword'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function registration()
    {
        if(User::findByEmail($this->email) === null && null === User::findByUsername($this->username)){
            if ($this->validate()) {
                $user = new User();
                $user->username = $this->username;
                $user->email = $this->email;
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                $user->created_at = date('Y-m-d');
                return $user->save();
            }
        }
        return false;
    }

    /**
     * Validate user password and password hash
     * @param $password
     * @param $confirmPassword
     * @return int
     */
    public function validatePassword($password, $confirmPassword)
    {
        return strcasecmp($password, $confirmPassword);
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
}
