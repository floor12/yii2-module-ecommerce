<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Payment;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Payment]].
 *
 * @see \floor12\ecommerce\models\entity\Payment
 */
class PaymentQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Payment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Payment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
