<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\City;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\City]].
 *
 * @see \floor12\ecommerce\models\entity\City
 */
class CityQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return City[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return City|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
