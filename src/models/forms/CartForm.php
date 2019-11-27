<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 11:08
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\DiscountGroup;
use floor12\ecommerce\models\entity\OrderItem;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\Item;
use yii\base\InvalidConfigException;
use yii\base\Model;

class CartForm extends Model
{
    public $total = 0;
    public $orderItems = [];
    public $messages = [];
    public $discount_items = [];

    /**
     * @inheritDoc
     * @throws  InvalidConfigException
     */
    public function init()
    {
        foreach ($_COOKIE as $name => $quantity) {

            if ($quantity < 0)
                $quantity = -1 * $quantity;

            if (preg_match('/cart-(\d+)/', $name, $matches)) {
                $productVariation = ProductVariation::findOne($matches[1]);
                if (!$productVariation)
                    continue;

                $this->orderItems[] = new OrderItem([
                    'product_variation_id' => $productVariation->id,
                    'quantity' => $quantity,
                    'price' => $productVariation->price_0,
                    'sum' => $productVariation->price_0 * $quantity,
                ]);

                $this->total = $this->total + $productVariation->price_0 * $quantity;
            }
        }
    }

    /**
     * @param Item $productVariation
     * @param int $quantity
     */
    public function processDiscount(ProductVariation $productVariation, int $quantity)
    {
        if ($quantity < 0)
            $quantity = -1 * $quantity;

        if (!empty($productVariation->discounts))
            foreach ($productVariation->discounts as $discount) {
                if (empty($this->discount_items[$discount->id])) {
                    $this->discount_items[$discount->id]['discount_group'] = $discount;
                    $this->discount_items[$discount->id]['quantity'] = $quantity;
                } else
                    $this->discount_items[$discount->id]['quantity'] = $this->discount_items[$discount->id]['quantity'] + $quantity;
                $this->discount_items[$discount->id]['active'] = $this->checkDiscountStatus($discount, $this->discount_items[$discount->id]['quantity']);
                if ($this->discount_items[$discount->id]['active'])
                    $this->messages[$discount->id] = $discount->description;
            }
    }

    /**
     * @param DiscountGroup $group
     * @param int $quantity
     * @return bool
     */
    public function checkDiscountStatus(DiscountGroup $group, int $quantity)
    {
        if ($group->item_quantity > 0 && $quantity >= $group->item_quantity)
            return true;
        return false;
    }

    /**
     * @param Item $productVariation
     * @return int
     */
    public function getPrice(ProductVariation $productVariation)
    {
        if (!empty($productVariation->discounts))
            foreach ($productVariation->discounts as $discount)
                if (!empty($this->discount_items[$discount->id]) && $this->discount_items[$discount->id]['active'] == true) {
                    if ($discount->discount_price_id) {
                        return $productVariation->{"price" . ++$discount->discount_price_id};
                    }
                }

        return $productVariation->price_current;
    }

    /**
     *  epmty cart
     */
    public function  empty()
    {
        foreach ($_COOKIE as $name => $quantity) {
            if (preg_match('/cart-(\d+)/', $name, $matches))
                setcookie($matches[0], '', time() - 3600, '/');
        }
    }

    public function cleanNotAvailble()
    {
//        if ($this->orderItems)
//            foreach ($this->orderItems as $key => $row) {
//                if (!$row['productVariation']->available)
//                    unset($this->orderItems[$key]);
//            }
    }
}