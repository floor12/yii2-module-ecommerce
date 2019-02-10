<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-12
 * Time: 07:36
 */

namespace floor12\ecommerce\logic;

use Codeception\Util\XmlBuilder;
use floor12\ecommerce\models\enum\OrderStatus;
use Yii;
use floor12\ecommerce\models\Order;
use yii\web\BadRequestHttpException;
use floor12\ecommerce\models\enum\PaymentType;

/**
 * Class OrderExport
 * @package floor12\ecommerce\logic
 */
class OrderExport
{
    /**
     * @var Order
     */
    protected $_order;

    public function __construct(Order $order)
    {
        $this->_order = $order;
        if ($this->_order->isNewRecord)
            throw new BadRequestHttpException('This order is not saved yet.');
        Yii::$app->getModule('shop');
    }

    public function execute()
    {
         $xml = new \Codeception\Util\XmlBuilder();
         $xml->order
             ->id
                ->val($this->_order->id)
                ->parent()
             ->datetime
                ->val(date('Y-m-d H:i:s',$this->_order->created))
                ->parent()
             ->status
                ->status_id
                     ->val($this->_order->status)
                     ->parent()
                ->status_description
                    ->val(OrderStatus::getLabel($this->_order->status))
                    ->parent()
                 ->parent()
             ->delivery
                ->type_id
                    ->val($this->_order->delivery_type_id)
                    ->parent()
                ->type_description
                    ->val(Yii::$app->getModule('shop')->deliveryTypes[$this->_order->delivery_type_id]['name'])
                    ->parent()
                ->parent()
             ->payment
                ->type_id
                    ->val($this->_order->payment_type_id)
                    ->parent()
                ->type_description
                     ->val(PaymentType::getLabel($this->_order->delivery_type_id))
                     ->parent()

                ->parent()
             ->amounts
                ->attr('currency',Yii::$app->getModule('shop')->currency)
                ->items
                    ->val($this->_order->items_cost)
                    ->parent()
                 ->delivery
                    ->val($this->_order->delivery_cost)
                    ->parent()
                 ->total
                    ->val($this->_order->total)
                    ->parent()
                 ->parent()
             ->client
                 ->name
                     ->val($this->_order->fullname)
                     ->parent()
                 ->email
                    ->val($this->_order->email)
                    ->parent()
                 ->phone
                    ->val($this->_order->phone)
                    ->parent()
                ->address
                    ->val($this->_order->address)
                    ->parent()
                ->parent()
             ->items;


        if ($this->_order->orderItems)
            foreach ($this->_order->orderItems as $item) {
                $xml
                    ->item
                        ->id
                            ->val($item->item->id)
                            ->parent()
                        ->external_id
                            ->val($item->item->external_id)
                            ->parent()
                        ->title
                            ->val($item->item->title)
                            ->parent()
                        ->price
                            ->val($item->item->price_current)
                            ->parent()
                        ->quantity
                            ->val($item->quantity)
                            ->parent()
                        ->amount
                            ->val($item->sum)
                            ->parent()
                    ->parent();
        }

        $fileName = Yii::getAlias(Yii::$app->getModule('shop')->exportPath) . DIRECTORY_SEPARATOR . $this->_order->id . '.xml';

        file_put_contents($fileName,$xml);

    }
}