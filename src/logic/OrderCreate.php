<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 12:54
 */

namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\entity\City;
use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\enum\DeliveryType;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentType;
use Yii;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class OrderCreate
{
    private $_model;
    private $_data;
    private $deliveries;
    private $deliveryTypeFromConfig;

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
        $this->deliveries = Yii::$app->getModule('shop')->deliveryTypes;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->_model->load($this->_data);

        $this->deliveryTypeFromConfig = $this->_model->delivery_type_id;
        if (!isset($this->deliveries[$this->_model->delivery_type_id]))
            throw new NotFoundHttpException('Delivery type is not found.');

        $this->_model->delivery_type_id = $this->deliveries[(int)$this->_model->delivery_type_id]['type'];

        // Если доставка не самовывоз - заполняем адрес и назание города
        if ($this->_model->delivery_type_id != DeliveryType::PICK_UP) {
            $cityName = City::findOne($this->_model->city);
            $this->_model->city_id = (int)$this->_model->city;
            $this->_model->address = "{$this->_model->postcode}, {$cityName}, {$this->_model->street} {$this->_model->building}, кв(оф) {$this->_model->apartament}";
        }

        // Если оплата не при получении, то выставляем статус "ожидание оплаты"
        if ($this->_model->payment_type_id != PaymentType::RECEIVING)
            $this->_model->status = OrderStatus::PAYMENT_EXPECTS;

        // Навешивем эвенты для пересчета стоимости доставки. очистки корзины, подсчета стоимости заказа и доставки
        $this->_model->on(Order::EVENT_AFTER_INSERT, function ($event) {

            $event->sender->products_cost = 0;
            $event->sender->products_weight = 0;

            foreach ($event->sender->cart->orderItems as $orderItem) {
                $orderItem->order_id = $event->sender->id;
                $orderItem->created = time();
                $orderItem->order_status = $event->sender->status;
                $event->sender->products_cost += $orderItem->sum;
                $event->sender->products_weight += $orderItem->productVariation->product->weight_delivery * $orderItem->quantity;
                if (!$orderItem->save())
                    throw new ErrorException('Order item saving error. ' . print_r($orderItem->errors, 1));
            }

            // Обновляем цену доставки
            $pricer = new DeliveryCost($this->deliveryTypeFromConfig, ['city_id' => $event->data['city_id'], 'weight' =>
                $event->sender->products_weight]);
            $event->sender->delivery_cost = $pricer->getPrice();

            // Добавляем стоимость доставки к основной цене
            $event->sender->total = $event->sender->products_cost + $event->sender->delivery_cost;
            $event->sender->save(false, ['products_cost', 'delivery_cost', 'products_weight', 'total']);

            $event->sender->cart->empty();

            $paymentLink = null;
            if ($this->_model->payment_type_id != PaymentType::RECEIVING)
                $paymentLink = Yii::$app->urlManager->createAbsoluteUrl(['/shop/frontend/cart/pay', 'order_id' => $this->_model->id]);

            //mail to admin
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/admin-new-order-html.php"],
                    ['model' => $event->sender]
                )
                ->setFrom([Yii::$app->params['no-replyEmail'] => Yii::$app->params['no-replyName']])
                ->setSubject(Yii::t('app.f12.ecommerce', 'New order'))
                ->setTo(Yii::$app->params['adminEmail'])
                ->send();

            //mail to client
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/client-new-order-html.php"],
                    ['model' => $event->sender, 'paymentLink' => $paymentLink]
                )
                ->setFrom([Yii::$app->params['no-replyEmail'] => Yii::$app->params['no-replyName']])
                ->setSubject(Yii::t('app.f12.ecommerce', 'Thanks for purchase'))
                ->setTo($event->sender->email)
                ->send();
        }, ['city_id' => (int)$this->_model->city]);

        return $this->_model->save();
    }


}
