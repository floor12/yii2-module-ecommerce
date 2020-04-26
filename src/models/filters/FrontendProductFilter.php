<?php


namespace floor12\ecommerce\models\filters;


use app\components\Pagination;
use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\enum\SortVariations;
use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\query\ProductQuery;
use Yii;
use yii\base\ErrorException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FrontendProductFilter extends Model
{
    /**
     * @var int
     */
    public $sort = SortVariations::SORT_NEW;
    /**
     * @var integer
     */
    public $price;
    /**
     * @var string
     */
    public $pageTitle;
    /**
     * @var bool
     */
    public $showDiscountOption = false;
    /**
     * @var integer
     */
    public $category_id;
    /**
     * @var Category
     */
    public $category;
    /**
     * @var array
     */
    public $category_list = [];
    /**
     * @var Parameter[]
     */
    public $parameters = [];
    /**
     * @var array Values from user form
     */
    public $values = [];
    /**
     * @var array Available parameters values
     */
    public $data = [];

    public $withImages = true;
    /**
     * @var int
     */
    public $priceMin = 0;
    /**
     * @var int
     */
    public $priceMinValue = 0;
    /**
     * @var int
     */
    public $priceMax = 0;
    /**
     * @var int
     */
    public $priceMaxValue = 0;
    /**
     * @var int
     */
    public $limit = 9;
    /**
     * @var int
     */
    public $offset = 0;
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
        SortVariations::SORT_NEW => 'ec_category.sort ASC, id DESC',
        SortVariations::SORT_PRICE_ASC => 'ec_product_variation.price_0 ASC',
        SortVariations::SORT_PRICE_DESC => 'ec_product_variation.price_0 DESC',
    ];

    /**
     * @return void
     * @throws ErrorException
     */
    public function prepare()
    {
        $this->findCategory();
        $this->findCategories();
        $this->findPrices();
        $this->findParameters();
        $this->findParametersData();
        $this->limit = Yii::$app->getModule('shop')->itemPerPage;
        $this->pageTitle = $this->category ? $this->category->title : Yii::t('app.f12.ecommerce', 'Catalog');
    }

    /**
     * @throws ErrorException
     */
    protected function findCategory()
    {
        if ($this->category_id) {
            $this->category = Category::findOne((int)$this->category_id);
            if (!$this->category)
                throw new ErrorException('Selected category no found.');
        }
    }

    /**
     * Find categories or sub categories
     */
    protected function findCategories()
    {
        $this->category_list = Category::find()
            ->active()
            ->orderBy('sort')
            ->dropdown();
    }

    /**
     * Load minimum and maximum prices for current category
     */
    protected function findPrices()
    {
        $priceQuery = ProductVariation::find()->active();
        $this->priceMin = intval($priceQuery->min('price_0'));
        $this->priceMax = intval($priceQuery->max('price_0'));

        if (empty($this->priceMaxValue))
            $this->priceMaxValue = $this->priceMax;

        if (empty($this->priceMinValue))
            $this->priceMinValue = $this->priceMin;
    }

    /**
     * Load parameters array
     */
    protected function findParameters()
    {
        $this->parameters = Parameter::find()
            ->root()
            ->indexBy('id')
            ->orderBy('sort')
            ->all();
    }

    /**
     * Find available values for input widgets
     */
    protected function findParametersData()
    {

        foreach ($this->parameters as $parameterId => $parameter) {
            $parameterDataQuery = $parameter->getParameterValues();

            if (!empty($this->category))
                $parameterDataQuery = $parameter->getParameterValues()
                    ->leftJoin('ec_parameter_value_product_variation',
                        'ec_parameter_value_product_variation.parameter_value_id = ec_parameter_value.id')
                    ->leftJoin('ec_product_variation',
                        'ec_product_variation.id = ec_parameter_value_product_variation.product_variation_id')
                    ->leftJoin('ec_product',
                        'ec_product.id = ec_product_variation.product_id')
                    ->leftJoin('ec_product_category',
                        'ec_product.id = ec_product_category.product_id')
                    ->andWhere([
                        'ec_product.status' => Status::ACTIVE,
                        'ec_product_category.category_id' => $this->category->id
                    ]);

            $this->data[$parameterId] = $parameterDataQuery
                ->select('value')
                ->indexBy('id')
                ->column();
        }
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function rules()
    {
        return [
            [['category_id', 'sort', 'limit', 'offset'], 'integer'],
            [['priceMinValue', 'priceMaxValue'], 'double'],
            ['values', 'safe']
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
            ->andWhere(['BETWEEN', 'ec_product_variation.price_0', $this->priceMinValue, $this->priceMaxValue])
            ->orderBy($this->sortExpressions[$this->sort]);

        if ($this->category)
            $this->query->category($this->category);

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
        if (empty($this->parameters))
            return;

        $values = [];
        foreach ($this->parameters as $parameterId => $parameter) {

            if (!isset($this->values[$parameterId]) || empty($this->values[$parameterId]))
                continue;

            foreach ($this->values[$parameterId] as $row)
                $values = array_merge($values, $this->values[$parameterId]);
        }

        if ($values)
            $this->query
                //   ->leftJoin('ec_product_variation', ' ec_product.id=ec_product_variation.product_id')
                ->leftJoin('ec_parameter_value_product_variation', 'ec_parameter_value_product_variation.product_variation_id = ec_product_variation.id')
                ->andWhere(['IN', 'ec_parameter_value_product_variation.parameter_value_id', $values]);


    }


}
