<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\DiscountGroup;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\DiscountGroup]].
 *
 * @see \floor12\ecommerce\models\entity\DiscountGroup
 */
class DiscountGroupQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DiscountGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DiscountGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
