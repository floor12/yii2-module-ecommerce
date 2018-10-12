<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcOrderItem]].
 *
 * @see \floor12\ecommerce\models\EcOrderItem
 */
class EcOrderItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcOrderItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcOrderItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
