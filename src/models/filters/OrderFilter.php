<?php

namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\entity\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderFilter represents the model behind the search form of `floor12\ecommerce\models\entity\Order`.
 */
class OrderFilter extends Model

{
    public $filter;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['filter', 'string'],
            ['status', 'integer']
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = Order::find()
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['OR',
                ['LIKE', 'id', $this->filter],
                ['LIKE', 'fullname', $this->filter],
                ['LIKE', 'address', $this->filter],
                ['LIKE', 'email', $this->filter],
                ['LIKE', 'phone', $this->filter],
                ['LIKE', 'comment', $this->filter],
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
