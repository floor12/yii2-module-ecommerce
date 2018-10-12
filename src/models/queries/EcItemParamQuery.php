<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcItemParam]].
 *
 * @see \floor12\ecommerce\models\EcItemParam
 */
class EcItemParamQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParam[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItemParam|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
