<?php


namespace floor12\ecommerce\components;


use floor12\ecommerce\models\entity\ProductVariation;

class CookieCart
{
    protected $productVariations = [];
    protected $quantity = [];

    public function __construct()
    {
        foreach ($_COOKIE as $name => $quantity) {

            if ($quantity < 0)
                $quantity = -1 * $quantity;

            if (preg_match('/cart-(\d+)/', $name, $matches)) {
                $productVariation = ProductVariation::findOne($matches[1]);
                if (!$productVariation)
                    continue;
                $this->productVariations[] = $productVariation;
                $this->quantity[$productVariation->id] = $quantity;
            }
        }
    }

    public function getQuantityTotal()
    {
        return array_sum($this->quantity);
    }


    public function getProductVariationIds()
    {
        return array_keys($this->quantity);
    }

    public function getProductVariationQuantity(int $productVariationId)
    {
        if ($this->quantity[$productVariationId])
            return $this->quantity[$productVariationId];
        return false;
    }

    public function getProductVariations()
    {
        return $this->productVariations;
    }
}
