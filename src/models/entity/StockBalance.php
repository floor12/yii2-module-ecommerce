<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\StockBalanceQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stock_balance".
 *
 * @property int $id
 * @property int $product_variation_id Product variation
 * @property int $stock_id Stock
 * @property int $balance Stock balance
 *
 * @property ProductVariation $productVariation
 * @property Stock $stock
 */
class StockBalance extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_stock_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_variation_id', 'stock_id'], 'required'],
            [['product_variation_id', 'stock_id', 'balance'], 'integer'],
            [['product_variation_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductVariation::class, 'targetAttribute' => ['product_variation_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'product_variation_id' => Yii::t('app.f12.ecommerce', 'Product variation'),
            'stock_id' => Yii::t('app.f12.ecommerce', 'Stock'),
            'balance' => Yii::t('app.f12.ecommerce', 'Stock balance'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProductVariation()
    {
        return $this->hasOne(ProductVariation::class, ['id' => 'product_variation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * {@inheritdoc}
     * @return StockBalanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StockBalanceQuery(get_called_class());
    }
}
