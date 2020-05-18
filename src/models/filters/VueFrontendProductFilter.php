<?php


namespace floor12\ecommerce\models\filters;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\SortVariations;
use floor12\ecommerce\models\query\ProductQuery;
use yii\base\Model;
use yii\db\Expression;

class VueFrontendProductFilter extends Model
{
    /**
     * @var int
     */
    public $sort = SortVariations::SORT_NEW;
    /**
     * @var integer
     */
    public $category_id;
    /**
     * @var bool
     */
    public $withImages = true;
    /**
     * @var int
     */
    public $price_min;
    /**
     * @var int
     */
    public $price_max;
    /**
     * @var int
     */
    public $limit = 12;
    /**
     * @var int
     */
    public $offset = 0;
    /** @var array */
    public $parameterValuesSelected = [];
    /**
     * @var array
     */
    public $sortVariations = [];
    /**
     * @var ProductQuery
     */
    protected $query;

    /**
     * @var array
     */
    protected $sortExpressions = [
        SortVariations::SORT_NEW => 'ec_category.sort ASC, ec_product.id DESC',
        SortVariations::SORT_PRICE_ASC => 'ec_product_variation.price_0 ASC',
        SortVariations::SORT_PRICE_DESC => 'ec_product_variation.price_0 DESC',
    ];


    /**
     * @inheritDoc
     * @return array
     */
    public function rules()
    {
        return [
            [['price_max', 'category_id', 'price_min', 'sort', 'limit', 'offset'], 'integer'],
            ['parameterValuesSelected', 'safe']
        ];
    }

    /**
     * @return ProductQuery
     */
    public function prepareQuery()
    {
        if (!empty($this->query))
            return $this->query;
        $this->query = Product::find()
            ->distinct()
            ->leftJoin('ec_product_category', 'ec_product_category.product_id=ec_product.id')
            ->leftJoin('ec_product_variation', 'ec_product_variation.product_id=ec_product.id')
            ->leftJoin('ec_category', 'ec_product_category.category_id=ec_category.id')
            ->active()
            ->andWhere(['!=', 'ec_product_variation.price_0', 0])
            ->andFilterWhere(['>=', 'ec_product_variation.price_0', $this->price_min])
            ->andFilterWhere(['<=', 'ec_product_variation.price_0', $this->price_max])
            ->orderBy($this->sortExpressions[$this->sort]);
        if (is_numeric($this->category_id))
            $this->query->category(Category::findOne($this->category_id));

        $this->applyValuesToQuery();

        return $this->query;
    }

    /**
     * @return bool|int|string|null
     */
    public function count()
    {
        return $this->prepareQuery()->count();
    }

    public function priceMin()
    {
        $expression = new Expression('MIN(ec_product_variation.price_0)');
        $query = clone $this->prepareQuery();
        return \intval($query->select($expression)->scalar());
    }

    public function priceMax()
    {
        $expression = new Expression('MAX(ec_product_variation.price_0)');
        $query = clone $this->prepareQuery();
        return \intval($query->select($expression)->scalar());
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        $query = clone $this->prepareQuery();
        return $query
            ->limit($this->limit)
            ->offset($this->offset)
            ->all();
    }

    /**
     * Analyze parameters values and add it main product query.
     */
    protected function applyValuesToQuery()
    {
        if (empty($this->parameterValuesSelected))
            return;
        $values = [];
        foreach ($this->parameterValuesSelected as $parameterId => $parameterValues) {
//            var_dump($parameterValuesAsString);die();
//            $parameterValues = explode(',', $parameterValuesAsString);
            if (empty($parameterValues))
                continue;
            foreach ($parameterValues as $parameterValue) {
                if (!empty($parameterValue))
                    $values[] = $parameterValue;
            }
        }
      
        if ($values)
            $this->query
                ->leftJoin('ec_parameter_value_product_variation', 'ec_parameter_value_product_variation.product_variation_id = ec_product_variation.id')
                ->andWhere(['IN', 'ec_parameter_value_product_variation.parameter_value_id', $values]);
    }
}
