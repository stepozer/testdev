<?php
/**
 * Created by PhpStorm.
 * User: sasha2567
 * Date: 05.08.16
 * Time: 17:13
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\RegistrationForm;

class UserController extends Controller
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
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect('../site/index');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Registration action.
     *
     * @return string
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();

        if (!Yii::$app->user->isGuest) {
            return $this->render('registration', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->registration()) {
            $this->redirect('login');
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        $this->redirect('login');
    }
}
