<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcItemParamValue]].
 *
 * @see \floor12\ecommerce\models\EcItemParamValue
 */
class EcItemParamValueQuery extends \yii\db\ActiveQuery
{

    /**
     * @param integer $param_id
     * @return EcItemParamValueQuery
     */
    public function param(int $param_id)
    {
        return $this->andWhere(['param_id' => $param_id]);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParamValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParamValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
