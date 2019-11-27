<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\ProductVariationQuery;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_variation".
 *
 * @property int $id
 * @property int $product_id Link to product
 * @property string $external_id External ID
 * @property float|null $price_0 First price
 * @property float|null $price_1 Second price
 * @property float|null $price_2 Third price
 * @property float|null $price_old Previous price
 *
 * @property OrderItem[] $orderItems
 * @property ParameterValue[] $parameterValues
 * @property Product $product
 * @property StockBalance[] $stockBalances
 */
class ProductVariation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_product_variation';
    }

    /**
     * {@inheritdoc}
     * @return ProductVariationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductVariationQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_0', 'price_1', 'price_2', 'price_old'], 'number'],
            ['external_id', 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'product_id' => Yii::t('app.f12.ecommerce', 'Link to product'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External ID'),
            'price_0' => Yii::t('app.f12.ecommerce', 'First price'),
            'price_1' => Yii::t('app.f12.ecommerce', 'Second price'),
            'price_2' => Yii::t('app.f12.ecommerce', 'Third price'),
            'price_old' => Yii::t('app.f12.ecommerce', 'Previous price'),
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getParameterValues()
    {
        return $this->hasMany(ParameterValue::class, ['id' => 'parameter_value_id'])
            ->viaTable('ec_parameter_value_product_variation', ['product_variation_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStockBalances()
    {
        return $this->hasMany(StockBalance::class, ['product_variation_id' => 'id']);
    }
}
