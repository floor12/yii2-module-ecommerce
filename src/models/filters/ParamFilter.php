<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:49
 */


namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\EcItemParam;
use floor12\ecommerce\models\queries\EcParamQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ParamFilter
 * @package floor12\ecommerce\models\filters
 * @property string $filter
 * @property integer $status
 * @property EcParamQuery $_query
 */
class ParamFilter extends Model
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
        $this->_query = EcItemParam::find()
            ->andFilterWhere(['=', 'status', $this->status]);


        return new ActiveDataProvider([
            'query' => $this->_query
        ]);
    }

}