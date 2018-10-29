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

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_options = Item::find()
            ->where(['parent_id' => $this->item->id])
            ->active()
            ->available()
            ->all();
        $this->_options[] = $this->item;

        $this->_cartItem = new CartItem();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        return $this->render('addToCartWidget', ['options' => $this->_options]);
    }
}