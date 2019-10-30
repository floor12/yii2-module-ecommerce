<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\Category;
use floor12\ecommerce\models\Item;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\ItemParamValue]].
 *
 * @see \floor12\ecommerce\models\ItemParamValue
 */
class ItemParamValueQuery extends \yii\db\ActiveQuery
{
    /**
     * @return ItemParamValueQuery
     */
    public function hasActiveItem()
    {
        return $this->andWhere('item_id IN (SELECT id FROM ec_item WHERE parent_id=0 AND status=0)');
    }

    /**
     * @param integer $param_id
     * @return ItemParamValueQuery
     */
    public function param(int $param_id)
    {
        return $this->andWhere(['param_id' => $param_id]);
    }

    /**
     * @param Category $category
     * @return ItemParamValueQuery
     */
    public function category(Category $category)
    {
        $items_ids = Item::find()
            ->category($category)
            ->active()
            ->select('id')
            ->column();

        return $this->andWhere(['IN', 'parent_item_id', $items_ids]);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\ItemParamValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\ItemParamValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
