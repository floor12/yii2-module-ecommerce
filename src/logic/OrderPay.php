<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 07:36
 */

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\Order;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class OrderPay
 * @package floor12\ecommerce\logic
 */
class OrderPay
{
    /**
     * @var Order
     */
    protected $_order;

    public function __construct(Order $order)
    {
        $this->_order = $order;
        if ($this->_order->payment_type_id == PaymentType::RECEIVING)
            throw new BadRequestHttpException('This order doesnt need online payment');
    }

    public function execute()
    {
        $publicKey = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_id'];
        $privateKey = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_pass'];
        $currency = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['currency'];

        $client = new \CloudPayments\Manager($publicKey, $privateKey);
        $transaction = $client->chargeToken($this->_order->total, $currency, $this->_order->email, '123');
        print $transaction->getId();
    }
}