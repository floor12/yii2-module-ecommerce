<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 20:45
 */

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\Order;
use floor12\ecommerce\models\Payment;
use Yii;
use yii\web\BadRequestHttpException;

class PaymentCreate
{
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var Payment
     */
    protected $payment;


    public function __construct(Order $order)
    {
        $this->order = $order;

        if ($this->order->status != OrderStatus::PAYMENT_EXPECTS)
            throw new BadRequestHttpException(Yii::t('app.f12.ecommerce', 'This order no expects payment.'));
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->payment = Payment::find()
            ->where([
                'order_id' => $this->order->id,
                'status' => [PaymentStatus::NEW, PaymentStatus::IN_PROCESS],
                'type' => $this->order->payment_type_id,
            ])
            ->one();

        if ($this->payment)
            return true;

        $this->payment = new Payment();
        $this->payment->created = time();
        $this->payment->updated = time();
        $this->payment->order_id = $this->order->id;
        $this->payment->sum = $this->order->total;
        $this->payment->status = PaymentStatus::NEW;
        $this->payment->type = $this->order->payment_type_id;
        return $this->payment->save();
    }

}