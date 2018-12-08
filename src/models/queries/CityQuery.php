<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\City;

/**
 * This is the ActiveQuery class for [[EcCity]].
 *
 * @see City
 */
class CityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EcCity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EcCity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
