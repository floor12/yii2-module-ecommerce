<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\DiscountGroup;

/**
 * This is the ActiveQuery class for [[\app\models\DiscountGroup]].
 *
 * @see DiscountGroup
 */
class DiscountGroupQuery extends \yii\db\ActiveQuery
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
