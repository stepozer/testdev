<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            // $this->redirect('user/login');
        }
        $user = new \app\models\User();
        $user->username = 'u1';
        $user->email = 'u1';
        $user->password = 'u1';
        $user->created_at = date('Y-m-d');
        $user->updated_at = date('Y-m-d');
        $user->save();

        echo '<pre>';
        print_r(\app\models\User::find()->all());
        echo '</pre>';

        return $this->render('index');
    }
}
