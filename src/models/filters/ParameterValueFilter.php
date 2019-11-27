<?php

namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\entity\ParameterValue;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * ParameterValueFilter represents the model behind the search form of `floor12\ecommerce\models\entity\ParameterValue`.
 */
class ParameterValueFilter extends Model

{
    public $filter;
    public $parameter_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['filter', 'string'],
            ['parameter_id', 'integer']
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Validation error');

        $query = ParameterValue::find()
            ->andFilterWhere(['like', 'value', $this->filter])
            ->andWhere(['parameter_id' => $this->parameter_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC]]
        ]);

        return $dataProvider;
    }
}
