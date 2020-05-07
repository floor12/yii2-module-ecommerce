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
    public $date_start;
    public $date_end;
    protected $date_start_timestamp;
    protected $date_end_timestamp;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['filter', 'string'],
            ['status', 'integer'],
            [['date_start', 'date_end'], 'string'],
        ];
    }

    protected function getQuery()
    {
        if (!$this->validate())
            return false;

        if ($this->date_end) {
            $this->date_end_timestamp = strtotime("{$this->date_end} 23:59:59");
        }

        if ($this->date_start) {
            $this->date_start_timestamp = strtotime("{$this->date_start} 00:00:00");
        }

        $query = Order::find()
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['>=', 'created', $this->date_start_timestamp])
            ->andFilterWhere(['<=', 'created', $this->date_end_timestamp])
            ->andFilterWhere(['OR',
                ['LIKE', 'id', $this->filter],
                ['LIKE', 'fullname', $this->filter],
                ['LIKE', 'address', $this->filter],
                ['LIKE', 'email', $this->filter],
                ['LIKE', 'phone', $this->filter],
                ['LIKE', 'comment', $this->filter],
            ]);

        return $query;
    }

    public function getArray()
    {
        return $this->getQuery()->all();
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->getQuery(),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $dataProvider;
    }
}
