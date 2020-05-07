<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\enum\ParameterType;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Parameter]].
 *
 * @see \floor12\ecommerce\models\entity\Parameter
 */
class ParameterQuery extends ActiveQuery
{

    /**
     * @return ParameterQuery
     */
    public function checkbox()
    {
        return $this->andWhere(['type_id' => ParameterType::CHECKBOX]);
    }


    /**
     * @return ParameterQuery
     */
    public function slider()
    {
        return $this->andWhere(['type_id' => ParameterType::SLIDER]);
    }

    /**
     * @return ParameterQuery
     */
    public function root()
    {
        return $this->andWhere("id NOT IN (SELECT parameter_id FROM ec_parameter_category)");
    }

    /**
     * @param array $category_ids
     * @return $this
     */
    public function byCategoyIds(array $category_ids)
    {
        if (empty($category_ids))
            return $this->andWhere('id NOT IN (SELECT parameter_id FROM ec_parameter_category)');
        $ids = implode(',', $category_ids);
        return $this->andWhere(['OR',
            "id IN (SELECT parameter_id FROM ec_parameter_category WHERE category_id IN ({$ids}) )",
            "id NOT IN (SELECT parameter_id FROM ec_parameter_category)"]);
    }

    /**
     * @param int $productId
     * @return $this
     */
    public function byProductId(int $productId)
    {
        return $this
            ->leftJoin('ec_parameter_value', 'ec_parameter_value.parameter_id = ec_parameter.id')
            ->leftJoin('ec_parameter_value_product_variation', 'ec_parameter_value_product_variation.parameter_value_id = ec_parameter_value.id')
            ->leftJoin('ec_product_variation', 'ec_product_variation.id = ec_parameter_value_product_variation.product_variation_id')
            ->andWhere(['ec_product_variation.product_id' => $productId]);
    }

    /**
     * @return ParameterQuery
     */
    public function noCategory()
    {
        return $this->andWhere("id NOT IN (SELECT parameter_id FROM ec_parameter_category)");
    }

    /**
     * @return ParameterQuery
     */
    public function active()
    {
        return $this->andWhere(['hide' => false]);
    }

    /**
     * @return array
     */
    public function dropdown()
    {
        return $this
            ->select('title')
            ->indexBy('id')
            ->orderBy('title')
            ->column();
    }


    /**
     * {@inheritdoc}
     * @return Parameter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Parameter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
