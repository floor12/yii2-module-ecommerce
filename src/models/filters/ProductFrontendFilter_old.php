<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:32
 */

namespace floor12\ecommerce\models\filters;


use app\components\Pagination;
use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\enum\ParameterType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ProductFrontendFilter
 * @package floor12\ecommerce\models\filters
 * @property Parameter[] $params
 * @property Parameter[] $chechbox_params
 * @property Parameter[] $slider_params
 * @property integer $category_id
 * @property string $category_title
 * @property array $param_values
 */
class ProductFrontendFilterOld extends Model
{
    public $category_id;
    public $category_title;
    public $selected_category_id;
    public $sub_categories = [];
    public $price;
    public $params = [];
    public $slider_params = [];
    public $checkbox_params = [];
    public $param_values = [];
    public $price_min;
    public $price_max;
    public $discount = false;
    public $showDiscountOption = false;
    public $filter;

    private $_category;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->category_id) {
            $this->_category = Category::findOne((int)$this->category_id);
            $this->category_title = $this->_category->title;
            $this->slider_params = $this->_category->getSlider_params()->active()->all();
            $this->checkbox_params = $this->_category->getCheckbox_params()->active()->all();
            $this->price_min = (int)Product::find()->active()->category($this->_category)->min('price');
            $this->price_max = (int)Product::find()->active()->category($this->_category)->max('price');

            $this->sub_categories = Category::find()
                ->orderBy('sort')
                ->hasActiveProducts()
                ->active()
                ->andWhere(['parent_id' => $this->category_id])
                ->select('title')
                ->indexBy('id')
                ->column();

            $this->showDiscountOption = Product::find()
                ->active()
                ->select('id')
                ->category($this->_category)
                ->andWhere("!ISNULL(price_discount)")
                ->scalar();

        } else {
            $this->sub_categories = Category::find()
                ->orderBy('sort')
                ->hasActiveProducts()
                ->active()
                ->andWhere('ISNULL(parent_id)')
                ->select('title')
                ->indexBy('id')
                ->column();

            $this->category_title = Yii::t('app.f12.ecommerce', $this->discount ? 'Sale' : 'Catalog');
            $this->price_min = (int)ProductVariation::find()->active()->min('price_0');
            $this->price_max = (int)ProductVariation::find()->active()->max('price_0');
            $this->slider_params = array_merge($this->slider_params, Parameter::find()->root()->slider()->active()->all());
            $this->checkbox_params += array_merge($this->checkbox_params, Parameter::find()->root()->checkbox()->active()->all());
            $this->showDiscountOption = false;
//                Product::find()
//                ->active()
//                ->select('id')
//                ->andWhere("!ISNULL(price_discount)")
//                ISNULL->scalar();
        }

        //  $this->sub_categories[] = Yii::t('app.f12.ecommerce', 'All categories');


        $this->params = array_merge($this->slider_params, $this->checkbox_params);

        parent::init();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['filter', 'string'],
            ['selected_category_id', 'integer'],
            [['param_values', 'price', 'discount'], 'safe']
        ];
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->_category;
    }


    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = Product::find()
            ->leftJoin('ec_item_category', 'ec_item_category.item_id=ec_item.id')
            ->leftJoin('ec_category', 'ec_item_category.category_id=ec_category.id')
            ->active()
            ->with('images')
            ->andFilterWhere(['LIKE', 'ec_item.title', $this->filter])
            ->orderBy('ec_category.sort')
            ->root();


        if ($this->selected_category_id)
            $query->category(Category::findOne($this->selected_category_id));
        elseif ($this->category_id)
            $query->category(Category::findOne($this->category_id));


        if ($this->price) {
            list($price_min, $price_max) = explode(';', $this->price);
            $query->andWhere(['OR', ['BETWEEN', 'price', $price_min, $price_max], ['BETWEEN', 'price_discount', $price_min, $price_max]]);
        }

        if ($this->discount)
            $query->andWhere(['!=', 'price_discount', '0']);


        foreach ($this->param_values as $param_id => $param_value) {

            if (!$param_value)
                continue;

            $parameter = Parameter::findOne($param_id);

            if ($parameter->type_id == ParameterType::CHECKBOX) {
                $param_value = array_map(
                    function ($el) {
                        return "'{$el}'";
                    },
                    $param_value
                );
                $values = implode(',', $param_value);
                $query->andWhere("ec_item.id IN (SELECT parent_item_id FROM " . ParameterValue::tableName() . " WHERE param_id={$param_id} AND value IN ({$values}))");
            }

            if ($parameter->type_id == ParameterType::SLIDER) {
                list($min, $max) = explode(';', $param_value);
                if ($min && $max)
                    $query->andWhere("ec_item.id IN (SELECT parent_item_id FROM " . ParameterValue::tableName() . " WHERE param_id={$param_id} AND value BETWEEN $min AND $max)");
            }

        }


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'class' => Pagination::class,
                'route' => parse_url(Yii::$app->request->url, PHP_URL_PATH),
                'defaultPageSize' => Yii::$app->getModule('shop')->itemPerPage
            ],
        ]);
    }

    /**
     * @param int $param_id
     * @return Parameter
     */
    private function getParameter(int $param_id)
    {
        foreach ($this->params as $param)
            if ($param->id == $param_id)
                return $param;
        return null;
    }
}