<?php


namespace floor12\ecommerce\components;


use floor12\ecommerce\models\entity\Product;
use yii\base\Widget;
use yii\helpers\Html;

class ProductVariationsWidget extends Widget
{
    /**
     * @var Product
     */
    public $model;
    /**
     * @var string
     */
    public $class = 'table table-striped';
    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @inheritDoc
     * @return string|void
     */
    public function run()
    {
        if (!is_object($this->model) || empty($this->model->variations))
            return;

        foreach ($this->model->variations as $variation)
            $this->rows[] = $this->render('productVariationRow', ['model' => $variation]);

        return Html::tag('table', implode(PHP_EOL, $this->rows), ['class' => $this->class]);
    }
}