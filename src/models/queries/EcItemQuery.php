<?php

namespace floor12\ecommerce\models\queries;

use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\enum\Status;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\EcItem]].
 *
 * @see \floor12\ecommerce\models\EcItem
 */
class EcItemQuery extends \yii\db\ActiveQuery
{


    private $_categories = [];

    /**
     * @return EcItemQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Status::ACTIVE]);
    }

    /**
     * @param int $category_id
     * @return EcItemQuery
     */
    public function category(EcCategory $category)
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
     * @return \floor12\ecommerce\models\EcItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\EcItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
