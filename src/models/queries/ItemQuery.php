<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\Category;
use floor12\ecommerce\models\enum\Status;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\Item]].
 *
 * @see \floor12\ecommerce\models\Item
 */
class ItemQuery extends \yii\db\ActiveQuery
{


    private $_categories = [];

    /** List as array for dropdowns
     * @return array
     */
    public function dropdown()
    {
        return $this
            ->select('title')
            ->indexBy('id')
            ->column();
    }

    /** Ignore items options
     * @return ItemQuery
     */
    public function root()
    {
        return $this->andWhere(['parent_id' => 0]);
    }

    /**
     * @return ItemQuery
     */
    public function available()
    {
        return $this->andWhere(['!=', 'available', 0]);
    }

    /**
     * @return ItemQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Status::ACTIVE]);
    }

    /**
     * @param int $category_id
     * @return ItemQuery
     */
    public function category(Category $category)
    {
        $this->addCategory([$category]);

        $ids = array_map(function ($cat) {
            return "'$cat->id'";
        }, $this->_categories);
        $cat_ids = implode(',', $ids);
        return $this->andWhere("id IN (SELECT item_id FROM ec_item_category WHERE category_id IN ({$cat_ids}))");
    }


    private function addCategory(array $categories)
    {
        $this->_categories = array_merge($this->_categories, $categories);
        foreach ($categories as $category)
            $this->addCategory($category->children);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\Item[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\Item|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
