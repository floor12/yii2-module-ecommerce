<?php


namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\forms\CartForm;
use Yii;
use yii\web\View;

class CheckoutTagRegister
{
    protected $model;
    protected $view;

    public function __construct(CartForm $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function register()
    {
        $products = [];
        foreach ($this->model->rows as $row) {
            $products[] = [
                'id' => $row['item']->id,
                'name' => $row['item']->title,
                'category' => $row['item']->categories ? $row['item']->categories[0]->title : NULL,
                'quantity' => $row['quantity'],
                'variant' => Yii::createObject(ParamProcessor::class, [$row['item']])->getParamsInString()
            ];
        }
        $productsJson = json_encode($products);
        $this->view->registerJs("f12Tag.checkout({$productsJson})");
    }

}