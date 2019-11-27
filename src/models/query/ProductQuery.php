<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\Status;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Product]].
 *
 * @see \floor12\ecommerce\models\entity\Product
 */
class ProductQuery extends ActiveQuery
{

    /**
     * @var array
     */
    private $categories = [];

    public function hasImages()
    {

    }

    /**
     * @param Category $category
     * @return ProductQuery
     */
    public function category(Category $category)
    {
        $this->addCategory([$category]);

        $ids = array_map(function ($cat) {
            return "'$cat->id'";
        }, $this->categories);
        $cat_ids = implode(',', $ids);
        return $this->andWhere("ec_product.id IN (SELECT product_id FROM ec_product_category WHERE category_id IN ({$cat_ids}))");
    }

    /**
     * @param array $categories
     */
    private function addCategory(array $categories)
    {
        $this->categories = array_merge($this->categories, $categories);
        foreach ($categories as $category)
            $this->addCategory($category->children);
    }

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

    /**
     * @return ProductQuery
     */
    public function active()
    {
        return $this->andWhere(['ec_product.status' => Status::ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
