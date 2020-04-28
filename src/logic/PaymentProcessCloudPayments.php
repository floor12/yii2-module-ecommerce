<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 21:50
 */

namespace floor12\ecommerce\logic;


use ErrorException;
use floor12\ecommerce\models\entity\Payment;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\enum\PaymentType;
use Yii;
use yii\web\BadRequestHttpException;

class PaymentProcessCloudPayments
{
    /**
     * @var Payment
     */
    protected $model;
    /**
     * @var array
     */
    protected $params;

    public function __construct(Payment $payment, array $params)
    {
        $this->model = $payment;
        $this->params = $params;

        if ($this->model->status != PaymentStatus::NEW)
            throw new BadRequestHttpException('This invoice no expects payment');
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->model->status = PaymentStatus::SUCCESS;
        $this->model->payed = time();
        $this->model->comment = json_encode($this->params);
        $this->model->external_id = $this->params['TransactionId'];

        if (!$this->model->save(true, ['status', 'payed', 'comment', 'external_id']))
            throw new ErrorException('Error with saving payment:' . print_r($this->model->errors, true));

        $this->model->order->status = OrderStatus::PAYED;
        $this->model->order->updated = time();
        if (!$this->model->order->save(true, ['status', 'updated']))
            throw new ErrorException('Error with saving payment');

        $this->sendEmailToAdmins();
        return true;
    }

    protected function sendEmailToAdmins()
    {
        Yii::$app
            ->mailer
            ->compose(
                ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/admin-new-payment-html.php"],
                ['model' => $this->model]
            )
            ->setFrom([Yii::$app->params['no-replyEmail'] => Yii::$app->params['no-replyName']])
            ->setSubject(Yii::t('app.f12.ecommerce', 'New success payment'))
            ->setTo(Yii::$app->params['adminEmail'])
            ->send();
    }

    public function getSign()
    {
        return md5($this->model->id . Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_pass']);
    }
}
