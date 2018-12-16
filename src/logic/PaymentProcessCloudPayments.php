<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 21:50
 */

namespace floor12\ecommerce\logic;


use ErrorException;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\Payment;
use yii\web\BadRequestHttpException;

class PaymentProcessCloudPayments
{
    /**
     * @var Payment
     */
    protected $_payment;
    /**
     * @var array
     */
    protected $params;

    public function __construct(Payment $payment, array $params)
    {
        $this->_payment = $payment;
        $this->params = $params;

        if ($this->_payment->status != PaymentStatus::NEW)
            throw new BadRequestHttpException('This invoice no expects payment');
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->_payment->status = PaymentStatus::SUCCESS;
        $this->_payment->payed = time();
        $this->_payment->comment = json_encode($this->params);
        $this->_payment->external_id = $this->params['TransactionId'];

        if (!$this->_payment->save(true, ['status', 'payed', 'comment', 'external_id']))
            throw new ErrorException('Error with saving payment:' . print_r($this->_payment->errors, true));

        $this->_payment->order->status = OrderStatus::PAYED;
        $this->_payment->order->updated = time();
        if (!$this->_payment->order->save(true, ['status', 'updated']))
            throw new ErrorException('Error with saving payment');

        return true;
    }


    public function getSign()
    {
        return md5($this->_payment->id . Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_pass']);
    }
}