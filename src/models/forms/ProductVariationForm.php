<?php


namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\logic\parameters\ParameterValueFinder;
use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\entity\StockBalance;
use Yii;
use yii\base\ErrorException;
use yii\base\Model;

class ProductVariationForm extends Model
{
    /**
     * @var string
     */
    public $external_id;
    /**
     * @var float
     */
    public $price_0;
    /**
     * @var float
     */
    public $price_1;
    /**
     * @var float
     */
    public $price_2;
    /**
     * @var int
     */
    public $id;
    /**
     * @var array
     */
    public $parameters = [];
    /**
     * @var array
     */
    public $parameterValues = [];
    /**
     * @var array
     */
    public $stocks = [];
    /**
     * @var array
     */
    public $stockBalances = [];
    /**
     * @var bool
     */
    public $isNewRecord = false;
    /**
     * @var ProductVariation
     */
    protected $productVariation;
    /**
     * @var \floor12\ecommerce\models\entity\Product
     */
    protected $product;
    /**
     * @var array
     */
    protected $categories = [];
    /**
     * @var array
     */
    protected $parameterValueIds = [];

    /**
     * ProductVariationForm constructor.
     * @param ProductVariation $productVariation
     * @throws ErrorException
     */
    public function __construct(ProductVariation $productVariation)
    {
        $this->productVariation = $productVariation;
        $this->product = $productVariation->product;
        $this->id = $productVariation->id;
        $this->price_0 = $productVariation->price_0;
        $this->price_1 = $productVariation->price_1;
        $this->price_2 = $productVariation->price_2;
        $this->isNewRecord = $productVariation->isNewRecord;

        if (!is_object($this->product))
            throw new ErrorException('This variation has no product.');

        $this->findParameters();
        $this->findStocks();

        if (!$this->isNewRecord) {
            $this->loadParameterValues();
            $this->loadStocksBalances();
        }

        parent::__construct([]);
    }

    /**
     * Stocks list loading
     */
    protected function findStocks()
    {
        $this->stocks = Stock::find()
            ->orderBy('title')
            ->indexBy('id')
            ->all();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->productVariation->attributeLabels();
    }

    /**
     * If product variant is not new record, load stock balances
     */
    protected function loadStocksBalances()
    {
        if (empty($this->stocks))
            return;

        foreach ($this->stocks as $stock)
            $this->stockBalances[$stock->id] = StockBalance::find()
                ->byStockId($stock->id)
                ->byProductVarioationId($this->productVariation->id)
                ->select('balance')
                ->scalar();
    }

    /**
     * If product variant is not new record, load parameters values
     */
    protected function loadParameterValues()
    {
        if (empty($this->parameters))
            return;

        foreach ($this->parameters as $parameter) {
            $this->parameterValues[$parameter->id] = ParameterValue::find()
                ->select('value')
                ->where(['parameter_id' => $parameter->id])
                ->andWhere("id IN (SELECT parameter_value_id FROM ec_parameter_value_product_variation WHERE product_variation_id={$this->productVariation->id})")->scalar();
        }
    }

    /**
     * Parameters loading depends of product categories
     */
    protected function findParameters()
    {
        if ($this->product->category_ids) {
            foreach ($this->product->categories as $category)
                $this->categories = array_merge($this->categories, Category::find()->withParents($category)->all());

            foreach ($this->categories as $category) {
                if (!empty($category->parameters))
                    foreach ($category->parameters as $parameter)
                        $this->parameters[$parameter->id] = $parameter;
            }
        }

        $parametersWithoutCategories = Parameter::find()
            ->orderBy('type_id')
            ->active()
            ->noCategory()
            ->all();

        if (empty($parametersWithoutCategories))
            return;

        foreach ($parametersWithoutCategories as $parameter)
            $this->parameters[$parameter->id] = $parameter;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['price_0', 'price_1', 'price_2'], 'number'],
            ['external_id', 'string', 'max' => 255],
            [['parameterValues', 'stockBalances'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        $this->productVariation->load([
            'external_id' => $this->external_id,
            'price_0' => $this->price_0,
            'price_1' => $this->price_1,
            'price_2' => $this->price_2,
        ], '');

        if (!empty($this->parameterValues))
            $this->prepareParameterValues();

        $this->productVariation->on(ProductVariation::EVENT_AFTER_INSERT, function () {
            $this->saveValues();
            $this->saveBalances();
        });

        $this->productVariation->on(ProductVariation::EVENT_AFTER_UPDATE, function () {
            $this->saveValues();
            $this->saveBalances();
        });

        return $this->productVariation->save();
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function saveValues()
    {
        Yii::$app->db
            ->createCommand()
            ->delete('ec_parameter_value_product_variation', ['product_variation_id' => $this->productVariation->id])
            ->execute();

        foreach ($this->parameterValueIds as $valueId)
            Yii::$app->db
                ->createCommand()
                ->insert('ec_parameter_value_product_variation', [
                    'ec_parameter_value_product_variation.product_variation_id' => $this->productVariation->id,
                    'ec_parameter_value_product_variation.parameter_value_id' => $valueId
                ])->execute();
    }

    /**
     * @throws ErrorException
     */
    protected function saveBalances()
    {
        StockBalance::deleteAll(['product_variation_id' => $this->productVariation->id]);
        if (empty($this->stockBalances))
            return;

        foreach ($this->stockBalances as $stockId => $stockBalance) {
            $stockBalanceModel = new StockBalance([
                'product_variation_id' => $this->productVariation->id,
                'stock_id' => $stockId,
                'balance' => (int)$stockBalance
            ]);

            if (!$stockBalanceModel->save())
                throw new ErrorException('Stock balance saving error.');
        }

    }

    /**
     *  Creating an array with parameters ids and values
     */
    protected function prepareParameterValues()
    {
        foreach ($this->parameterValues as $parameterId => $parameterValue) {
            if (empty($parameterValue))
                continue;
            $this->parameterValueIds[] = Yii::createObject(ParameterValueFinder::class, [
                $parameterValue,
                $this->parameters[$parameterId]
            ])->getValueId();
        }
    }
}
