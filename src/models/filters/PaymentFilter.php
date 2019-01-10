<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:49
 */


namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\Payment;
use floor12\ecommerce\models\queries\EcParamQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class PaymentFilter
 * @property integer $status Payment status
 * @property string $date Date of payment
 * @package floor12\ecommerce\models\filters
 */
class PaymentFilter extends Model
{
    public $status;
    public $date;

    private $_query;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['date'], 'string'],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $this->_query = Payment::find()
            ->andFilterWhere(['=', 'status', $this->status]);


        return new ActiveDataProvider([
            'query' => $this->_query
        ]);
    }

}