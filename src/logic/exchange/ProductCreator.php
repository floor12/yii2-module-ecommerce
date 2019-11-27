<?php


namespace floor12\ecommerce\logic\exchange;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\Status;
use Yii;
use yii\base\ErrorException;

class ProductCreator
{
    /**
     * @var string
     */
    protected $productName;
    /**
     * @var string
     */
    protected $productFullName;
    /**
     * @var string
     */
    protected $productUID;
    /**
     * @var string
     */
    protected $productArticle;
    /**
     * @var Category
     */
    protected $category_ids;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var string
     */
    protected $productDescription;

    /**
     * CategoryCreator constructor.
     * @param string $categoryName
     */
    public function __construct(string $productUID, string $productName, string $productFullName, $productArticle, $productDescription,
                                array $category_ids)
    {
        $this->productName = $productName;
        $this->productFullName = $productFullName;
        $this->productUID = $productUID;
        $this->productArticle = $productArticle;
        $this->category_ids = $category_ids;
        $this->productDescription = $productDescription;
    }

    /**
     * @return Product
     * @throws ErrorException
     */
    public function getPoduct()
    {
        if (empty($this->product))
            $this->findOrCreateProduct();
        return $this->product;
    }

    /**
     * @return Product
     * @throws ErrorException
     */
    protected function findOrCreateProduct()
    {
        $this->product = Product::find()
            ->where(['external_id' => $this->productUID])
            ->one();

        if (is_object($this->product))
            return $this->product;

        $this->product = new Product([
            'title' => $this->productFullName,
            'external_id' => $this->productUID,
            'status' => Status::ACTIVE,
            'article' => $this->productArticle,
            'description' => $this->productDescription,
            'category_ids' => $this->category_ids,
            'weight_delivery' => Yii::$app->getModule('shop')->defaultDeliveryWeight
        ]);

        if (!$this->product->save())
            throw new ErrorException('Error product saving: ' . print_r($this->product->getFirstErrors(), 1));

        return $this->product;
    }


}