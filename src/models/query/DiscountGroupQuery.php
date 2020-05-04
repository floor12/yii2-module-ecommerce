<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\DiscountGroup;
use floor12\ecommerce\models\enum\Status;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\DiscountGroup]].
 *
 * @see \floor12\ecommerce\models\entity\DiscountGroup
 */
class DiscountGroupQuery extends ActiveQuery
{
    /**
     * @return DiscountGroupQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Status::ACTIVE]);
    }

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
