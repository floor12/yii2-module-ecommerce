<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\OrderItemQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int|null $user_id Buyer indificator
 * @property int $product_variation_id Item identificator
 * @property int $created Created
 * @property int $order_id Order identificator
 * @property float $price Product current price
 * @property float $full_price Product full price
 * @property integer $discount_group_id Discount group id
 * @property integer $discount_percent Discount in percent
 * @property int $order_status Order status
 * @property int $quantity Quantity of product
 * @property float|null $sum Total sum
 *
 * @property ProductVariation $productVariation
 * @property Order $order
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_variation_id', 'created', 'order_id', 'order_status', 'quantity', 'discount_group_id', 'discount_percent'], 'integer'],
            [['product_variation_id', 'created', 'order_id', 'price', 'quantity'], 'required'],
            [['price', 'sum', 'full_price'], 'number'],
            [['product_variation_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductVariation::class, 'targetAttribute' => ['product_variation_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'user_id' => Yii::t('app.f12.ecommerce', 'Buyer indificator'),
            'product_variation_id' => Yii::t('app.f12.ecommerce', 'Item identificator'),
            'created' => Yii::t('app.f12.ecommerce', 'Created'),
            'order_id' => Yii::t('app.f12.ecommerce', 'Order identificator'),
            'price' => Yii::t('app.f12.ecommerce', 'Item price'),
            'order_status' => Yii::t('app.f12.ecommerce', 'Order status'),
            'quantity' => Yii::t('app.f12.ecommerce', 'Quantity of product'),
            'sum' => Yii::t('app.f12.ecommerce', 'Total sum'),
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
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderItemQuery(get_called_class());
    }
}
