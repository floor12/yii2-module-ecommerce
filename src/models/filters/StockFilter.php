<?php

namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\entity\Stock;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * StockFilter represents the model behind the search form of `floor12\ecommerce\models\entity\Stock`.
 */
class StockFilter extends Model

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
     * @throws BadRequestHttpException
     */
    public function dataProvider()
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Filter validation error.');

        $query = Stock::find()
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['LIKE', 'title', $this->filter]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }
}
