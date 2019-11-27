<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/10/2018
 * Time: 20:27
 */

namespace floor12\ecommerce\components;


use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\forms\ProductSelectorForm;
use yii\base\ErrorException;
use yii\base\Widget;

class ParameterSelectorWidget extends Widget
{
    /**
     * @var Product
     */
    public $product;

    /**
     * @var array
     */
    public $parameterValues = [];
    /**
     * @var ProductSelectorForm
     */
    public $model;
    /**
     * @var Parameter[]
     */
    protected $parameters = [];
    /**
     * @var array
     */
    protected $parameterValuesList = [];
    /**
     * @var array
     */
    protected $stockBalances = [];
    /**
     * @var ProductVariation
     */
    protected $productVariation;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->product))
            throw new ErrorException('Product not found.');

        $this->parameters = Parameter::find()
            ->active()
            ->byProductId($this->product->id)
            ->orderBy('sort')
            ->all();

        if (!empty($this->parameters))
            foreach ($this->parameters as $parameter) {
                $this->parameterValuesList[$parameter->id] = ParameterValue::find()
                    ->leftJoin('ec_parameter_value_product_variation',
                        'ec_parameter_value_product_variation.parameter_value_id = ec_parameter_value.id')
                    ->leftJoin('ec_product_variation',
                        'ec_product_variation.id = ec_parameter_value_product_variation.product_variation_id')
                    ->andWhere([
                        'ec_product_variation.product_id' => $this->product->id,
                        'ec_parameter_value.parameter_id' => $parameter->id
                    ])
                    ->orderBy('sort')
                    ->select('value')
                    ->groupBy('ec_parameter_value.id')
                    ->indexBy('id')
                    ->column();

                if (sizeof($this->parameterValuesList[$parameter->id]) == 1)
                    $this->model->parameterValueIds[$parameter->id] = array_key_first($this->parameterValuesList[$parameter->id]);

            }

        $this->stockBalances = Stock::find()->balancesByProductId($this->product->id, $this->model->parameterValueIds)
            ->asArray()
            ->all();

        if ($this->stockBalances)
            $this->productVariation = ProductVariation::find()
                ->leftJoin('ec_parameter_value_product_variation', 'ec_parameter_value_product_variation.product_variation_id = ec_product_variation.id')
                ->andWhere(['ec_product_variation.product_id' => $this->product->id])
                ->andWhere(['ec_parameter_value_product_variation.parameter_value_id' => $this->model->parameterValueIds])
                ->groupBy(' ec_product_variation.id')
                ->having(['count(ec_product_variation.id)' => sizeof($this->parameters)])
                ->one();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        return $this->render('parameterSelectorWidget', [
            'parameters' => $this->parameters,
            'stockBalances' => $this->stockBalances,
            'parameterValuesList' => $this->parameterValuesList,
            'model' => $this->model,
            'product' => $this->product,
            'producatVariation' => $this->productVariation
        ]);
    }
}