<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\ParameterValue;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\ParameterValue]].
 *
 * @see \floor12\ecommerce\models\entity\ParameterValue
 */
class ParameterValueQuery extends ActiveQuery
{

    /**
     * @param array $product_variation_ids
     * @return ParameterValueQuery
     */
    public function byProductVariations(array $product_variation_ids)
    {
        if (empty($product_variation_ids))
            return $this->andWhere('false');
        
        return $this->leftJoin('ec_parameter_value_product_variation', 'ec_parameter_value.id=ec_parameter_value_product_variation.parameter_value_id')
            ->andWhere(['IN', 'ec_parameter_value_product_variation.product_variation_id', $product_variation_ids]);
    }

    /**
     * @param int $parameter_id
     * @return ParameterValueQuery
     */
    public function byParameterId(int $parameter_id)
    {
        return $this->andWhere(['parameter_id' => $parameter_id]);
    }

    /**
     * {@inheritdoc}
     * @return ParameterValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ParameterValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
