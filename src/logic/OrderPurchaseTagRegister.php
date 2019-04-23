<?php


namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\Order;
use Yii;
use yii\web\View;

class OrderPurchaseTagRegister
{
    protected $model;
    protected $view;

    public function __construct(Order $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function register()
    {
        $orderJson = json_encode([
            'id' => $this->model->id,
            'revenue' => $this->model->items_cost,
            'shipping' => $this->model->delivery_cost,
        ]);
        $products = [];
        foreach ($this->model->orderItems as $orderItem) {
            $products = [
                'id' => $orderItem->item->id,
                'name' => $orderItem->item->title,
                'category' => $orderItem->item->categories ? $orderItem->item->categories[0]->title : NULL,
                'quantity' => $orderItem->quantity,
                'price' => $orderItem->price,
                'variant' => Yii::createObject(ParamProcessor::class, [$orderItem->item])->getParamsInString()
            ];
        }
        $productsJson = json_encode($products);
        $this->view->registerJs("f12Tag.productPurchase({$orderJson},{$productsJson})");
    }

}