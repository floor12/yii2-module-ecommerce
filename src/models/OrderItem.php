<?php

namespace floor12\ecommerce\models;

use Yii;

/**
 * This is the model class for table "ec_order_item".
 *
 * @property int $id
 * @property int $user_id Buyer indificator
 * @property int $item_id Item identificator
 * @property int $created Created
 * @property int $order_id Order identificator
 * @property int $quantity Quantity
 * @property float $price Item price
 * @property float $sum Item price
 * @property int $order_status Order status
 *
 * @property Item $item
 * @property Order $order
 */
class OrderItem extends \yii\db\ActiveRecord
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
            [['item_id', 'created', 'order_id', 'price'], 'required'],
            [['price', 'sum'], 'double'],
            [['user_id', 'item_id', 'created', 'order_id', 'order_status'], 'integer'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'item_id' => Yii::t('app.f12.ecommerce', 'Item identificator'),
            'created' => Yii::t('app.f12.ecommerce', 'Created'),
            'order_id' => Yii::t('app.f12.ecommerce', 'Order identificator'),
            'price' => Yii::t('app.f12.ecommerce', 'Item price'),
            'order_status' => Yii::t('app.f12.ecommerce', 'Order status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\OrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\ecommerce\models\queries\OrderItemQuery(get_called_class());
    }
}
