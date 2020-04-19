<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 20:16
 */

namespace floor12\ecommerce\controllers\frontend;

use floor12\ecommerce\logic\PaymentProcessCloudPayments;
use floor12\ecommerce\models\entity\Payment;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PaymentController extends Controller
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionPay()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $payment_id = (int)Yii::$app->request->post('InvoiceId');

        $payment = Payment::findOne($payment_id);

        if (!$payment)
            throw new NotFoundHttpException('Invoice not found');

        Yii::getLogger()->log('Invoice is found', 1);


        Yii::createObject(PaymentProcessCloudPayments::class, [$payment, Yii::$app->request->post()])->execute();

        return ['code' => 0];
    }
}
