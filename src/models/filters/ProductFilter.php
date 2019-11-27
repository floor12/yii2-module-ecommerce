<?php

namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\entity\Product;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * ProductFilter represents the model behind the search form of `floor12\ecommerce\models\entity\Product`.
 */
class ProductFilter extends Model

{
    public $filter;
    public $status;
    public $category_id;
    public $hideOptions = 0;
    public $withoutExternal = 0;

    private $_query;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status', 'hideOptions', 'withoutExternal', 'category_id'], 'integer'],
            [['filter'], 'string'],
        ];
    }

    /**
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function dataProvider()
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Search model validation error.');


        $this->_query = Product::find()
            ->leftJoin('ec_product_category', 'ec_product_category.product_id=ec_product.id')
            ->with('categories')
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['ec_product_category.category_id' => $this->category_id])
            ->andFilterWhere(['OR',
                ['LIKE', 'id', $this->filter],
                ['LIKE', 'title', $this->filter],
                ['LIKE', 'article', $this->filter],
                ['LIKE', 'external_id', $this->filter],
            ]);

        if ($this->withoutExternal)
            $this->_query->andWhere('ISNULL(external_id) OR `external_id`=""');

        $dataProvider = new ActiveDataProvider([
            'query' => $this->_query,
            'sort' => [
                'attributes' => ['id' => ['default' => SORT_ASC]],
                'defaultOrder' => ['id' => SORT_ASC]]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id' => [
                    'default' => SORT_ASC
                ],
            ],
            'defaultOrder' => [
                'id' => SORT_ASC
            ]
        ]);

        return $dataProvider;
    }

    /**@inheritdoc
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'withoutExternal' => Yii::t('app.f12.ecommerce', 'without external ID')
        ];
    }
}
