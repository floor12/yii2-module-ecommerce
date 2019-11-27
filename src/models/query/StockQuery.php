<?php

namespace floor12\ecommerce\models\query;

use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\enum\Status;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\floor12\ecommerce\models\entity\Stock]].
 *
 * @see \floor12\ecommerce\models\entity\Stock
 */
class StockQuery extends ActiveQuery
{
    /**
     * @param int $product_id
     * @param array $parameterValueIds
     * @param int $productStatus
     * @return StockQuery
     */
    public function balancesByProductId(int $productId, array $parameterValueIds = [], $productStatus = Status::ACTIVE)
    {
        $query = $this
            ->leftJoin('ec_stock_balance', 'ec_stock_balance.stock_id = ec_stock.id')
            ->leftJoin('ec_product_variation', 'ec_product_variation.id = ec_stock_balance.product_variation_id')
            ->andWhere(['ec_product_variation.product_id' => $productId])
            ->andWhere(['ec_stock.status' => $productStatus])
            ->andWhere(['>', 'ec_stock_balance.balance', 0])
            ->orderBy('ec_stock.sort')
            ->groupBy('ec_stock.id');

        if (empty($parameterValueIds))
            return $query;

        $productVariationsIds = [];
        foreach ($parameterValueIds as $key => $valueId) {
            $ids = Yii::$app->db
                ->createCommand("SELECT product_variation_id FROM ec_parameter_value_product_variation WHERE parameter_value_id=:value", [':value' => $valueId])
                ->queryColumn();
            if (empty($productVariationsIds))
                $productVariationsIds = $ids;
            else
                $productVariationsIds = array_uintersect($productVariationsIds, $ids, "strcasecmp");
        }
        return $query->andWhere(['ec_product_variation.id' => $productVariationsIds]);
    }

    /**
     * {@inheritdoc}
     * @return Stock[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Stock|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
