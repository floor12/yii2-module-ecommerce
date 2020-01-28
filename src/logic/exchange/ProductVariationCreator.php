<?php


namespace floor12\ecommerce\logic\exchange;


use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;
use yii\base\ErrorException;

class ProductVariationCreator
{

    /**
     * @var string
     */
    protected $productVariationUID;
    /**
     * @var ProductVariation
     */
    protected $productVariation;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var float
     */
    protected $price;


    /**
     * ProductCreator constructor.
     * @param string $productVariationUID
     * @param Product $product
     * @param array $attributes
     * @throws ErrorException
     */
    public function __construct(string $productVariationUID, Product $product, float $price, array $attributes = [])
    {
        if ($product->isNewRecord)
            throw new ErrorException('Product is not saved yet and cant have variations.');

        $this->productVariationUID = $productVariationUID;
        $this->product = $product;
        $this->attributes = $attributes;
        $this->price = $price;
    }

    /**
     * @return ProductVariation
     * @throws ErrorException
     */
    public function getVariation()
    {
        if (empty($this->productVariation))
            $this->findOrCreateProductVariation();
        return $this->productVariation;
    }

    /**
     * @return ProductVariation
     * @throws ErrorException
     */
    protected function findOrCreateProductVariation()
    {
        $this->productVariation = ProductVariation::find()
            ->where(['external_id' => $this->productVariationUID])
            ->one();

        if (!is_object($this->productVariation))
            $this->productVariation = new productVariation([
                'external_id' => $this->productVariationUID,
                'product_id' => $this->product->id,
            ]);

        $this->productVariation->price_0 = $this->price;
        if (!$this->productVariation->save())
            throw new ErrorException('Error product variation saving: ' . print_r($this->productVariation->getFirstErrors(), 1));
        
        return $this->productVariation;
    }


}
