<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\ItemParamValue]].
 *
 * @see \floor12\ecommerce\models\ItemParamValue
 */
class ItemParamValueQuery extends \yii\db\ActiveQuery
{

    /**
     * @param integer $param_id
     * @return ItemParamValueQuery
     */
    public function param(int $param_id)
    {
        return $this->andWhere(['param_id' => $param_id]);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\ItemParamValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\ItemParamValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
