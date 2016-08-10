<?php
/**
 * Created by PhpStorm.
 * User: sasha2567
 * Date: 05.08.16
 * Time: 17:14
 */

namespace app\controllers;


use app\models\Payment;
use yii\web\Controller;
use yii\data\Pagination;


class PaymentController extends Controller
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
     * Payment action.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Payment::getAllPayments();
        // get the total number of payment
        $count = $query->count();
        $data = null;
        $pagination = null;
        if($count){
            // create a pagination object with the total count
            $pagination = new Pagination(['totalCount' => $count]);
            // limit the query using the pagination and retrieve the data
            $data = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        }
        foreach ($data as $row){
            $row->ends_at = Payment::getDateOnFormat($row->ends_at);
            $row->starts_at = Payment::getDateOnFormat($row->starts_at);
        }
        $start = Payment::getStartData();
        $end = Payment::getEndData();
        return $this->render('payment', ['start' => $start, 'end' => $end, 'data' => $data, 'pagination' => $pagination]);
    }

    /**
     * Add new payment record action
     *
     */
    public function actionAdd()
    {
        Payment::generateNext();
        $this->redirect('index');
    }

    /**
     * Delete payment record by $id
     * @param $id
     */
    public function actionDelete($id)
    {
        (new Payment())->paymentDelete($id);
        $this->redirect('index');
    }
}
