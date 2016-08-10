<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return  User::find()->where(['user_id' => $id])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return array|ActiveRecord
     */
    public static function findByUsername($username)
    {
        $user = User::find()->where(['username' => $username])->one();
        return  $user ? $user : null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return array|ActiveRecord
     */
    public static function findByEmail($email)
    {
        $user = User::find()->where(['email' => $email])->one();
        return  $user ? $user : null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password, $passwordCorrect)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $passwordCorrect);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;//$this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;//$this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
}
