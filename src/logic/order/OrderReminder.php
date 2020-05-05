<?php

namespace floor12\ecommerce\logic\order;

use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\enum\OrderStatus;
use Yii;

class OrderReminder
{
    protected $ignoreOrderStatusIds = [
        OrderStatus::DONE,
        OrderStatus::CANCELED,
    ];
    /** @var Order[] */
    protected $orders = [];

    public function __construct(array $ignoreIrderStatusIds = [])
    {
        if (!empty($ignoreIrderStatusIds))
            $this->ignoreOrderStatusIds = $ignoreIrderStatusIds;
    }

    public function run()
    {
        $this->loadOrders();
        if (!empty($this->orders))
            $this->sendEmail();
    }

    protected function loadOrders()
    {
        $this->orders = Order::find()
            ->where(['!=', 'status', $this->ignoreOrderStatusIds])
            ->all();
    }

    protected function sendEmail()
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => "@vendor/floor12/yii2-module-ecommerce/src/mail/admin-order-reminder-html.php"],
                ['models' => $this->orders]
            )
            ->setFrom([Yii::$app->params['no-replyEmail'] => Yii::$app->params['no-replyName']])
            ->setSubject(Yii::t('app.f12.ecommerce', 'Отчет по текущем заказам'))
            ->setTo(Yii::$app->params['adminEmail'])
            ->send();
    }
}
