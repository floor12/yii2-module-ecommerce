<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\enum\Status;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Category]].
 *
 * @see \floor12\ecommerce\models\entity\Category
 */
class CategoryQuery extends ActiveQuery
{
    private $_categories = [];

    /**
     * @return $this
     */
    public function hasActiveProducts()
    {
        return $this->andWhere("id IN (SELECT category_id FROM ec_product_category WHERE ec_product_category.product_id IN (SELECT id FROM ec_product WHERE status=0))");
    }

    /**
     * @return CategoryQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Status::ACTIVE]);
    }

    /**
     * @return false|string
     */
    public function asJson()
    {
        return json_encode($this->select(['id', 'title'])
            ->active()
            ->orderBy('sort')
            ->asArray()
            ->all());
    }

    /**
     * @param Category $category
     * @return CategoryQuery
     */
    public function withParents(Category $category)
    {
        $this->addCategory($category);

        $ids = array_map(function ($cat) {
            return "$cat->id";
        }, $this->_categories);
        return $this->andWhere(['IN', 'id', $ids]);
    }

    /**
     * @param Category $category
     */
    private function addCategory(Category $category)
    {
        $this->_categories[] = $category;
        if ($category->parent)
            $this->addCategory($category->parent);
    }

    /**
     * @param bool $rootOnly
     * @return array
     */
    public function dropdown($rootOnly = true)
    {
        return $this
            ->select('fulltitle')
            ->indexBy('id')
            ->column();
    }

    /**
     * {@inheritdoc}
     * @return Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
