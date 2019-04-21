<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 11:08
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\DiscountGroup;
use floor12\ecommerce\models\Item;
use Yii;
use yii\base\Model;

class CartForm extends Model
{
    public $total = 0;
    public $rows = [];
    public $messages = [];
    public $discount_items = [];

    public function init()
    {
        foreach ($_COOKIE as $name => $quantity) {
            if (preg_match('/cart-(\d+)/', $name, $matches)) {
                $item = Item::findOne($matches[1]);

                if (!$item || !$item->available)
                    continue;

                $this->processDiscount($item, $quantity);

                $this->rows[$item->id] = [
                    'item' => $item,
                    'quantity' => $quantity,
                ];

            }
        }

        foreach ($this->rows as &$row) {
            $row['price'] = $row['item']->price_current;

            if (!empty($row['item']->discounts))
                foreach ($row['item']->discounts as $discount) {
                    if (!empty($this->discount_items[$discount->id] && $this->discount_items[$discount->id]['active'] == true)) {
                        if ($discount->discount_price_id) {
                            $row['price'] = $row['item']->{"price" . ++$discount->discount_price_id};
                            $row['message'] = $discount->description;
                        }
                    }
                }
            $row['sum'] = $row['quantity'] * $row['price'];
            $this->total = $this->total + $row['sum'];
            $row['price'] = Yii::$app->formatter->asCurrency($row['price'], Yii::$app->getModule('shop')->currency);
            $row['sum'] = Yii::$app->formatter->asCurrency($row['sum'], Yii::$app->getModule('shop')->currency);
        }

        ksort($this->rows);
        $this->total = Yii::$app->formatter->asCurrency($this->total, Yii::$app->getModule('shop')->currency);
    }

    /**
     * @param Item $item
     * @param int $quantity
     */
    public function processDiscount(Item $item, int $quantity)
    {
        if (!empty($item->discounts))
            foreach ($item->discounts as $discount) {
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
     * @param Item $item
     * @return int
     */
    public function getPrice(Item $item)
    {
        if (!empty($item->discounts))
            foreach ($item->discounts as $discount)
                if (!empty($this->discount_items[$discount->id] && $this->discount_items[$discount->id]['active'] == true)) {
                    if ($discount->discount_price_id) {
                        return $item->{"price" . ++$discount->discount_price_id};
                    }
                }

        return $item->price_current;
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
//        if ($this->rows)
//            foreach ($this->rows as $key => $row) {
//                if (!$row['item']->available)
//                    unset($this->rows[$key]);
//            }
    }
}