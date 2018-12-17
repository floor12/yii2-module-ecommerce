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
    public function dropbdown($rootOnly = true)
    {
        $rootCategories = $this
            ->select('title')
            ->indexBy('id')
            ->where('ISNULL(parent_id)')
            ->orderBy('title')
            ->column();

        if ($rootOnly)
            return $rootCategories;
        $ret = [];
        if ($rootCategories)
            foreach ($rootCategories as $id => $title) {
                $ret[$title] = $this
                    ->select('title')
                    ->indexBy('id')
                    ->where(['parent_id' => $id])
                    ->orderBy('title')
                    ->column();
            }
        return $ret;
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
