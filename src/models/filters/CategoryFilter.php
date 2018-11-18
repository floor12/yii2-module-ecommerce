<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:49
 */


namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\Category;
use floor12\ecommerce\models\queries\CategoryQuery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CategoryFilter
 * @package floor12\ecommerce\models\filters
 * @property string $filter
 * @property integer $status
 * @property CategoryQuery $_query
 */
class CategoryFilter extends Model
{
    public $filter;

    private $_query;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['filter'], 'string'],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $this->_query = Category::find()->andFilterWhere(['LIKE', 'title', $this->filter]);

        return new ActiveDataProvider([
            'query' => $this->_query,
            'pagination' => false
        ]);
    }

    public function attributeLabels()
    {
        return [
            'root' => Yii::t('app.f12.ecommerce', 'only root categories')
        ];
    }

}