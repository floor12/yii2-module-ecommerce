<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\Category;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\Category]].
 *
 * @see \floor12\ecommerce\models\Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    private $_categories = [];

    public function withParents(Category $category)
    {
        $this->addCategory($category);

        $ids = array_map(function ($cat) {
            return "$cat->id";
        }, $this->_categories);
        return $this->andWhere(['IN', 'id', $ids]);
    }

    private function addCategory(Category $category)
    {
        $this->_categories[] = $category;
        if ($category->parent)
            $this->addCategory($category->parent);
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
     * @return \floor12\ecommerce\models\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
