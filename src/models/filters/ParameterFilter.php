<?php

namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\entity\Parameter;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ParameterFilter represents the model behind the search form of `floor12\ecommerce\models\entity\Parameter`.
 */
class ParameterFilter extends Model

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
        $query = Parameter::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC]]
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
