<?php


namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\Item;
use Yii;
use yii\web\View;

class ItemsListTagRegister
{
    /**
     * @var Item[]
     */
    protected $models;
    /**
     * @var View
     */
    protected $view;

    /**
     * ItemsListTagRegister constructor.
     * @param array $models
     * @param View $view
     */
    public function __construct(array $models, View $view)
    {
        $this->models = $models;
        $this->view = $view;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getCode()
    {
        $products = [];
        foreach ($this->models as $model) {
            $products[] = [
                'id' => $model->id,
                'name' => $model->title,
                'category' => $model->categories ? $model->categories[0]->title : NULL,
                'variant' => Yii::createObject(ParamProcessor::class, [$model])->getParamsInString()
            ];
        }
        $productsJson = json_encode($products);
        return "f12Tag.productsListed({$productsJson})";
    }

}