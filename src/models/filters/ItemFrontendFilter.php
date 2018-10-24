<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:32
 */

namespace floor12\ecommerce\models\filters;


use app\components\Pagination;
use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\EcItem;
use floor12\ecommerce\models\EcItemParam;
use floor12\ecommerce\models\EcItemParamValue;
use floor12\ecommerce\models\enum\ParamType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ItemFrontendFilter
 * @package floor12\ecommerce\models\filters
 * @property EcItemParam[] $params
 * @property EcItemParam[] $chechbox_params
 * @property EcItemParam[] $slider_params
 * @property integer $category_id
 * @property string $category_title
 * @property ar\ $param_values
 */
class ItemFrontendFilter extends Model
{
    public $category_id;
    public $category_title;
    public $price;
    public $params = [];
    public $slider_params = [];
    public $checkbox_params = [];
    public $param_values = [];
    public $price_min;
    public $price_max;
    public $discount = false;

    private $_category;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->category_id) {
            $this->_category = EcCategory::findOne((int)$this->category_id);
            $this->category_title = $this->_category->title;
            $this->slider_params = $this->_category->getSlider_params()->active()->all();
            $this->checkbox_params = $this->_category->getCheckbox_params()->active()->all();
            $this->params = array_merge($this->slider_params, $this->checkbox_params);
            $this->price_min = (int)EcItem::find()->active()->category($this->_category)->min('price');
            $this->price_max = (int)EcItem::find()->active()->category($this->_category)->max('price');
        } else {
            $this->category_title = Yii::t('app.f12.ecommerce', 'Catalog');
        }
        parent::init();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['param_values', 'price', 'discount'], 'safe']
        ];
    }

    /**
     * @return EcCategory|null
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
        $query = EcItem::find()->with('images');

        if ($this->price) {
            list($price_min, $price_max) = explode(';', $this->price);
            $query->andWhere(['OR', ['BETWEEN', 'price', $price_min, $price_max], ['BETWEEN', 'price_discount', $price_min, $price_max]]);
        }

        if ($this->discount)
            $query->andWhere(['!=', 'price_discount', '0']);

        foreach ($this->param_values as $param_id => $param_value) {

            if (!$param_value)
                continue;

            $parameter = EcItemParam::findOne($param_id);

            if ($parameter->type_id == ParamType::CHECKBOX) {
                $param_value = array_map(
                    function ($el) {
                        return "'{$el}'";
                    },
                    $param_value
                );
                $values = implode(',', $param_value);
                $query->andWhere("id IN (SELECT item_id FROM " . EcItemParamValue::tableName() . " WHERE param_id={$param_id} AND value IN ({$values}))");
            }

            if ($parameter->type_id == ParamType::SLIDER) {
                list($min, $max) = explode(';', $param_value);
                if ($min && $max)
                    $query->andWhere("id IN (SELECT item_id FROM " . EcItemParamValue::tableName() . " WHERE param_id={$param_id} AND value BETWEEN $min AND $max)");
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
     * @return EcItemParam
     */
    private function getParameter(int $param_id)
    {
        foreach ($this->params as $param)
            if ($param->id == $param_id)
                return $param;
    }
}