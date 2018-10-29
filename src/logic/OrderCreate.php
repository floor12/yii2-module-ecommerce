<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 12:54
 */

namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\Order;
use floor12\ecommerce\models\OrderItem;
use Yii;
use yii\base\ErrorException;

class OrderCreate
{
    private $_model;
    private $_data;

    /**
     * OrderCreate constructor.
     * @param Order $model
     * @param array $data
     */
    public function __construct(Order $model, array $data)
    {
        $this->_model = $model;
        $this->_data = $data;
        $this->_model->scenario = Order::SCENARIO_CHECKOUT;
        $this->_model->created = $this->_model->updated = time();
        $this->_model->status = OrderStatus::ORDERED;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->_model->load($this->_data);

        $this->_model->on(Order::EVENT_AFTER_INSERT, function ($event) {

            $event->sender->total = 0;

            foreach ($event->sender->cart->rows as $row) {
                $orderItem = new OrderItem([
                    'order_id' => $event->sender->id,
                    'created' => time(),
                    'item_id' => $row['item']->id,
                    'price' => $row['item']->price_current,
                    'quantity' => (int)$row['quantity'],
                    'sum' => $row['quantity'] * $row['item']->price_current,
                    'order_status' => $event->sender->status,
                ]);
                $event->sender->total += $orderItem->sum;
                if (!$orderItem->save())
                    throw new ErrorException('Order item saving error. ' . print_r($orderItem->errors, 1));
            }

            $event->sender->save(false, ['total']);

            $event->sender->cart->empty();

            //mail to admin
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/admin-new-order-html.php"],
                    ['model' => $event->sender]
                )
                ->setFrom([Yii::$app->params['no-replayEmail'] => Yii::$app->params['no-replayName']])
                ->setSubject(Yii::t('app.f12.ecommerce', 'New order'))
                ->setTo(Yii::$app->params['adminEmail'])
                ->send();

            //mail to client
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/client-new-order-html.php"],
                    ['model' => $event->sender]
                )
                ->setFrom([Yii::$app->params['no-replayEmail'] => Yii::$app->params['no-replayName']])
                ->setSubject(Yii::t('app.f12.ecommerce', 'Thanks for purchase'))
                ->setTo($event->sender->email)
                ->send();
        });

        return $this->_model->save();
    }

}