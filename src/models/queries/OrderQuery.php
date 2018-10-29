<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\Order]].
 *
 * @see \app\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
