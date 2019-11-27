<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\StockBalance;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\StockBalance]].
 *
 * @see \floor12\ecommerce\models\entity\StockBalance
 */
class StockBalanceQuery extends ActiveQuery
{

    /**
     * @param int $product_variation_id
     * @return StockBalanceQuery
     */
    public function byProductVarioationId(int $product_variation_id)
    {
        return $this->andWhere(['product_variation_id' => $product_variation_id]);
    }

    /**
     * @param int $stock_id
     * @return StockBalanceQuery
     */
    public function byStockId(int $stock_id)
    {
        return $this->andWhere(['stock_id' => $stock_id]);
    }

    /**
     * @return false|string|null
     */
    public function total()
    {
        return $this->sum('balance');
    }

    /**
     * {@inheritdoc}
     * @return StockBalance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return StockBalance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
