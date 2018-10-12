<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcItem]].
 *
 * @see \floor12\ecommerce\models\EcItem
 */
class EcItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
