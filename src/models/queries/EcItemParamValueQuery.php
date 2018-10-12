<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcItemParamValue]].
 *
 * @see \floor12\ecommerce\models\EcItemParamValue
 */
class EcItemParamValueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParamValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParamValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
