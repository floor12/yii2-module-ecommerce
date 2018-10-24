<?php

namespace floor12\ecommerce\models\queries;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\ItemParam]].
 *
 * @see \floor12\ecommerce\models\ItemParam
 */
class ItemParamQuery extends \yii\db\ActiveQuery
{

    /**
     * @return ItemParamQuery
     */
    public function active()
    {
        return $this->andWhere(['hide' => false]);
    }

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
     * @return \floor12\ecommerce\models\ItemParam[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\ItemParam|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
