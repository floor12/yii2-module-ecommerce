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
    public $status;

    private $_query;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['filter'], 'string'],
        ];
    }

    public function dataProvider()
    {
        $this->_query = Category::find()
            ->andFilterWhere(['=', 'status', $this->status]);


        return new ActiveDataProvider([
            'query' => $this->_query
        ]);
    }

}