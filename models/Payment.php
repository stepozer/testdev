<?php
/**
 * Created by PhpStorm.
 * User: sasha2567
 * Date: 04.08.16
 * Time: 17:39
 */

namespace app\models;


use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;


/**
 * RegistrationForm is the model behind the registration form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Payment extends ActiveRecord
{
    const PAYMENTDAYS = [5, 20];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return  Payment::find()->where(['payment_id' => $id])->one();
    }

    /**
     * Get last payment record
     *
     * @return ActiveRecord
     */
    public static function getLastPaymentRecord()
    {
        return Payment::find()->orderBy('ends_at desc')->limit('1')->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function generateNext()
    {
        $payment = new Payment();
        $lastPayment = Payment::getLastPaymentRecord();
        $start = $lastPayment ? $lastPayment['ends_at'] : date('Y-m-d');
        $newStartData = strtotime("{$start} +1 day");
        $payment->starts_at = date('Y-m-d', $newStartData);
        $day = date("d", $newStartData);
        echo $payment->starts_at.' ';
        $end = 0;
        foreach (self::PAYMENTDAYS as $days){
            if($day < $days){
                $end = $days - 1;
                echo $end;
                break;
            }
        }
        $monthAddFlag = false;
        if(!$end){
            $end = self::PAYMENTDAYS[0]-1;
            $monthAddFlag = true;
        }
        $newEndData = strtotime("{$payment->starts_at}");
        if($monthAddFlag){
            $newEndData = strtotime("{$payment->starts_at} +1 month");
        }
        $tempDataArray = date('Y-m-d', $newEndData);
        list($year, $month, $day) = explode("-", $tempDataArray);
        $end = mktime(0, 0, 0, $month, $end, $year);
        $end = date('Y-m-d', $end);
        $payment->ends_at = $end;
        $payment->user_userid = Yii::$app->user->getId();
        return $payment->save();
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_userid']);
    }

    public static function getAllPayments()
    {
        $query = Payment::find()
            ->select('{{%payments}}.payment_id, {{%payments}}.starts_at, {{%payments}}.ends_at, {{%payments}}.user_userid, u.email')
            ->orderBy('{{%payments}}.payment_id')
            ->groupBy('{{%payments}}.payment_id, u.email')
            ->joinWith(['user u'], true, 'INNER JOIN');
        return $query;
    }

    public static function getStartData()
    {
        $dataStr = ($data = self::getAllPayments()->orderBy('starts_at')->limit('1')->one()) ? $data->starts_at : '';
        return self::getDateOnFormat($dataStr);
    }

    public static function getDateOnFormat($data)
    {
        $dataStr = strtotime($data);
        $dataStr = date('M j, Y', $dataStr);
        return $dataStr;
    }

    public static function getEndData()
    {
        $dataStr = ($data = self::getAllPayments()->orderBy('ends_at desc')->limit('1')->one()) ? $data->ends_at : '';
        return self::getDateOnFormat($dataStr);
    }

    public function paymentDelete($id)
    {
        $payment = Payment::findOne($id);
        $payment->delete();
    }
}
