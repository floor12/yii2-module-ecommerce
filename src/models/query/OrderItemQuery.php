<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\OrderItem;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\OrderItem]].
 *
 * @see \floor12\ecommerce\models\entity\OrderItem
 */
class OrderItemQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return OrderItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
