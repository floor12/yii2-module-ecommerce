<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 11:08
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\components\CookieCart;
use floor12\ecommerce\components\PriceCalculator;
use floor12\ecommerce\models\DiscountGroup;
use floor12\ecommerce\models\entity\OrderItem;
use floor12\ecommerce\models\Item;
use Yii;
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
        $cookieCart = new CookieCart();
        /** @var PriceCalculator $priceCalculator */
        $priceCalculator = Yii::$app->priceCalculator;
        $priceCalculator->setMode(PriceCalculator::MODE_CART);
        foreach ($cookieCart->getProductVariations() as $productVariation) {
            $priceCalculator->setProductVariation($productVariation);
            $quantity = $cookieCart->getProductVariationQuantity($productVariation->id);
            $sum = $priceCalculator->getCurrentPrice() * $quantity;
            $this->orderItems[] = new OrderItem([
                'product_variation_id' => $productVariation->id,
                'quantity' => $quantity,
                'price' => $priceCalculator->getCurrentPrice(),
                'full_price' => $priceCalculator->hasDiscount() ? $priceCalculator->getOldPrice() : $priceCalculator->getCurrentPrice(),
                'discount_percent' => $priceCalculator->getDiscountInPercent(),
                'discount_group_id' => $priceCalculator->getDiscountGroupId(),
                'sum' => $sum,
            ]);

            $this->total = $this->total + $sum;
        }

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
