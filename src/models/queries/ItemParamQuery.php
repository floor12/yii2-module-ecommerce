<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\enum\ParamType;
use floor12\ecommerce\models\Item;
use floor12\ecommerce\models\ItemParamValue;

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
    public function checkbox()
    {
        return $this->andWhere(['type_id' => ParamType::CHECKBOX]);
    }


    /**
     * @return ItemParamQuery
     */
    public function slider()
    {
        return $this->andWhere(['type_id' => ParamType::SLIDER]);
    }

    /**
     * @return ItemParamQuery
     */
    public function root()
    {
        return $this->andWhere("id NOT IN (SELECT param_id FROM ec_param_category)");
    }

    /**
     * @param Item $
     * @return $this
     */
    public function byItem(Item $item)
    {
        return $this->andWhere("id IN (SELECT param_id FROM " . ItemParamValue::tableName() . " WHERE parent_item_id=:item_id)", [':item_id' => $item->id]);
    }

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
