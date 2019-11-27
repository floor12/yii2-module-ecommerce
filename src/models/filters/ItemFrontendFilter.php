<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:32
 */

namespace floor12\ecommerce\models\filters;


use app\components\Pagination;
use floor12\ecommerce\models\Category;
use floor12\ecommerce\models\enum\ParameterType;
use floor12\ecommerce\models\Item;
use floor12\ecommerce\models\ItemParam;
use floor12\ecommerce\models\ItemParamValue;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ItemFrontendFilter
 * @package floor12\ecommerce\models\filters
 * @property ItemParam[] $params
 * @property ItemParam[] $chechbox_params
 * @property ItemParam[] $slider_params
 * @property integer $category_id
 * @property string $category_title
 * @property array $param_values
 */
class ItemFrontendFilter extends Model
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
            $this->price_min = (int)Item::find()->active()->category($this->_category)->min('price');
            $this->price_max = (int)Item::find()->active()->category($this->_category)->max('price');

            $this->sub_categories = Category::find()
                ->orderBy('sort')
                ->hasActiveItems()
                ->active()
                ->andWhere(['parent_id' => $this->category_id])
                ->select('title')
                ->indexBy('id')
                ->column();

            $this->showDiscountOption = Item::find()
                ->active()
                ->select('id')
                ->category($this->_category)
                ->andWhere("!ISNULL(price_discount)")
                ->scalar();

        } else {
            $this->sub_categories = Category::find()
                ->orderBy('sort')
                ->hasActiveItems()
                ->active()
                ->andWhere('ISNULL(parent_id)')
                ->select('title')
                ->indexBy('id')
                ->column();

            $this->category_title = Yii::t('app.f12.ecommerce', $this->discount ? 'Sale' : 'Catalog');
            $this->price_min = (int)Item::find()->active()->min('price');
            $this->price_max = (int)Item::find()->active()->max('price');
            $this->slider_params = array_merge($this->slider_params, ItemParam::find()->root()->slider()->active()->all());
            $this->checkbox_params += array_merge($this->checkbox_params, ItemParam::find()->root()->checkbox()->active()->all());
            $this->showDiscountOption = Item::find()
                ->active()
                ->select('id')
                ->andWhere("!ISNULL(price_discount)")
                ->scalar();
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
        $query = Item::find()
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

            $parameter = ItemParam::findOne($param_id);

            if ($parameter->type_id == ParameterType::CHECKBOX) {
                $param_value = array_map(
                    function ($el) {
                        return "'{$el}'";
                    },
                    $param_value
                );
                $values = implode(',', $param_value);
                $query->andWhere("ec_item.id IN (SELECT parent_item_id FROM " . ItemParamValue::tableName() . " WHERE param_id={$param_id} AND value IN ({$values}))");
            }

            if ($parameter->type_id == ParameterType::SLIDER) {
                list($min, $max) = explode(';', $param_value);
                if ($min && $max)
                    $query->andWhere("ec_item.id IN (SELECT parent_item_id FROM " . ItemParamValue::tableName() . " WHERE param_id={$param_id} AND value BETWEEN $min AND $max)");
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
     * @return ItemParam
     */
    private function getParameter(int $param_id)
    {
        foreach ($this->params as $param)
            if ($param->id == $param_id)
                return $param;
        return null;
    }
}