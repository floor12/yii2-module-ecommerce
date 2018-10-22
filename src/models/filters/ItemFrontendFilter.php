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
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ItemFrontendFilter
 * @package floor12\ecommerce\models\filters
 * @property EcItemParam[] $params
 * @property integer $category_id
 * @property string $category_title
 * @property ar\ $param_values
 */
class ItemFrontendFilter extends Model
{
    public $category_id;
    public $category_title;

    public $params = [];
    public $param_values = [];

    private $_category;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->category_id) {
            $this->_category = EcCategory::findOne((int)$this->category_id);
            $this->category_title = $this->_category->title;
            $this->params = $this->_category->params;
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
            ['param_values', 'safe']
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

        foreach ($this->param_values as $param_id => $param_value) {
            if (!$param_value)
                continue;

            $param_value = array_map(
                function ($el) {
                    return "'{$el}'";
                },
                $param_value
            );
            $values = implode(',', $param_value);
            $query->andWhere("id IN (SELECT item_id FROM " . EcItemParamValue::tableName() . " WHERE param_id=:param_id AND value IN ({$values}))", [':param_id' => $param_id]);
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
}