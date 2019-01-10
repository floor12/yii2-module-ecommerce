<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[Payment]].
 *
 * @see floor12\ecommerce\models\Payment
 */
class PaymentQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $status
     * @return PaymentQuery
     */
    public function byStatus(int $status)
    {
        return $this->andWhere(['status' => $status]);
    }

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
