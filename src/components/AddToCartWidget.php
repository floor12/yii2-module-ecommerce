<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/10/2018
 * Time: 20:27
 */

namespace floor12\ecommerce\components;


use floor12\ecommerce\models\CartItem;
use floor12\ecommerce\models\Item;
use yii\base\Widget;

class AddToCartWidget extends Widget
{
    public $item;

    private $_options;
    private $_cartItem;
    private $params;
    private $_showProceedBtn = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_options = Item::find()
            ->where(['parent_id' => $this->item->id])
            ->active()
            ->all();
        $this->_options[] = $this->item;

        foreach ($this->_options as $option) {
            if (!$option->itemParamValues)
                continue;
            foreach ($option->itemParamValues as $paramValue) {

            }
        }

        $this->_cartItem = new CartItem();

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