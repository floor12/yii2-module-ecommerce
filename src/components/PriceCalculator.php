<?php


namespace floor12\ecommerce\components;


use floor12\ecommerce\models\entity\DiscountGroup;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;

class PriceCalculator
{
    const MODE_CART = 0;
    const MODE_CATALOG = 1;
    /** @var ProductVariation */
    protected $model;
    protected $currentPrice;
    protected $oldPrice;
    protected $itemsInCart;
    protected $mode = self::MODE_CATALOG;
    protected $hasDiscount = false;
    protected $activeDiscountGroup;

    public function __construct()
    {
        $cart = new CookieCart();
        $this->itemsInCart = $cart->getQuantityTotal();
        $this->findActiveDiscoundGroup($this->itemsInCart + 1);
    }

    protected function findActiveDiscoundGroup($itemsInCart)
    {
        $this->activeDiscountGroup = DiscountGroup::find()
            ->active()
            ->andWhere(['<=', 'item_quantity', $itemsInCart])
            ->orderBy(['item_quantity' => SORT_DESC])
            ->one();
    }

    public function setProductVariation(ProductVariation $model)
    {
        $this->model = $model;
        $this->hasDiscount = false;
        $this->currentPrice = null;
        $this->oldPrice = null;
        $this->calucalateSimple();
        if ($this->hasDiscount == false && ($this->activeDiscountGroup !== null)) {
            $this->calucalateWithDiscounts();
        }
    }

    public function setProduct(Product $model)
    {
        if (empty($model->variations))
            return;
        $this->setProductVariation($model->variations[0]);
    }

    public function setMode(int $mode)
    {
        $this->mode = $mode;
        $x = $mode == self::MODE_CATALOG ? sizeof($this->itemsInCart) + 1 : $this->itemsInCart;
        $this->findActiveDiscoundGroup($x);
    }

    protected function calucalateSimple()
    {
        $this->currentPrice = $this->model->price_0;
        if ($this->model->price_old) {
            $this->hasDiscount = true;
            $this->oldPrice = $this->model->price_old;
        }
    }

    protected function calucalateWithDiscounts()
    {
        $this->oldPrice = $this->model->price_0;
        $this->hasDiscount = true;

        if ($this->activeDiscountGroup->discount_price_id == null) {
            $this->currentPrice = $this->model->price_0 - ($this->model->price_0 / 100 * $this->activeDiscountGroup->discount_percent);
        } else {
            $this->currentPrice = $this->model->price_{$this->activeDiscountGroup->discount_price_id};
        }

    }

    public function hasDiscount()
    {
        return $this->hasDiscount;
    }

    public function getCurrentPrice()
    {
        return $this->currentPrice;
    }

    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    public function getDiscountInPercent()
    {
        if ($this->oldPrice == 0)
            return 0;
        return 100 - round($this->currentPrice / $this->oldPrice * 100);
    }

    public function getDiscountGroupId()
    {
        if ($this->hasDiscount())
            return $this->activeDiscountGroup->id;
        return null;
    }

}
