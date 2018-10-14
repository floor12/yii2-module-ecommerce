<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcCategory]].
 *
 * @see \floor12\ecommerce\models\EcCategory
 */
class EcCategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     */
    public function dropbdown()
    {
        return $this
            ->select('title')
            ->indexBy('id')
            ->orderBy('title')
            ->column();
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
