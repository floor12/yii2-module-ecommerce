<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/10/2018
 * Time: 20:27
 */

namespace floor12\ecommerce\components;


use floor12\ecommerce\models\CartItem;
use floor12\ecommerce\models\entity\ProductVariation;
use yii\base\Widget;

class AddToCartWidget extends Widget
{
    public $product;

    private $_options;
    private $_cartProduct;
    private $params = [];
    private $_showProceedBtn = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_options = ProductVariation::find()
            ->active()
            ->all();
        $this->_options[] = $this->product;

        foreach ($this->_options as $option) {
            if (!$option->productParamValues)
                continue;
            foreach ($option->productParamValues as $paramValue) {

            }
        }

        $this->_cartProduct = new CartItem();

        foreach ($_COOKIE as $name => $value) {
            if (preg_match('/cart-\d+/', $name, $mathes))
                $this->_showProceedBtn = true;
        }
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        return $this->render('addToCartWidget', [
            'options' => $this->_options,
            'showProceedBtn' => $this->_showProceedBtn
        ]);
    }
}