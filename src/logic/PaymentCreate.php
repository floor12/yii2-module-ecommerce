<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 20:45
 */

namespace floor12\ecommerce\logic;

use Yii;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\Order;
use floor12\ecommerce\models\Payment;
use yii\web\BadRequestHttpException;

class PaymentCreate
{
    /**
     * @var Order
     */
    protected $_order;
    /**
     * @var Payment
     */
    protected $_payment;


    public function __construct(Order $order)
    {
        $this->_order = $order;
        $this->_payment = new Payment();

        if ($this->_order->status != OrderStatus::PAYMENT_EXPECTS)
            throw new BadRequestHttpException(Yii::t('app.f12.ecommerce', 'This order no expects payment.'));
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->_payment->created = time();
        $this->_payment->updated = time();
        $this->_payment->order_id = $this->_order->id;
        $this->_payment->sum = $this->_order->total;
        $this->_payment->status = PaymentStatus::NEW;
        $this->_payment->type = $this->_order->payment_type_id;
        return $this->_payment->save();
    }

}