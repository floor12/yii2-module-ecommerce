<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Order;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Order]].
 *
 * @see \floor12\ecommerce\models\entity\Order
 */
class OrderQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
