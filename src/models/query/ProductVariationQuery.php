<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\enum\Status;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\ProductVariation]].
 *
 * @see \floor12\ecommerce\models\entity\ProductVariation
 */
class ProductVariationQuery extends ActiveQuery
{
    public function active()
    {
        return $this
            ->leftJoin('ec_product', 'ec_product.id=ec_product_variation.product_id')
            ->andWhere(['ec_product.status' => Status::ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return ProductVariation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductVariation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
