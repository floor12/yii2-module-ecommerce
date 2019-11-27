<?php

namespace floor12\ecommerce\models\filters;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use floor12\ecommerce\models\entity\DiscountGroup;

/**
 * DiscountGroupFilter represents the model behind the search form of `floor12\ecommerce\models\entity\DiscountGroup`.
 */
class DiscountGroupFilter extends Model

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
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = DiscountGroup::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
