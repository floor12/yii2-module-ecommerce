<?php

namespace floor12\ecommerce\models;

use Yii;

/**
 * This is the model class for table "ec_order".
 *
 * @property int $id
 * @property int $user_id Buyer indificator
 * @property int $created Created
 * @property int $updated Updated
 * @property int $delivered Delivered
 * @property double $total Total cost
 * @property int $status Order status
 * @property string $external_id External id
 * @property int $delivery_status Delivery status
 *
 * @property OrderItem[] $ecOrderItems
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created', 'updated'], 'required'],
            ['external_id', 'string'],
            [['user_id', 'created', 'updated', 'delivered', 'status', 'delivery_status'], 'integer'],
            [['total'], 'number'],
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
            'created' => Yii::t('app.f12.ecommerce', 'Created'),
            'updated' => Yii::t('app.f12.ecommerce', 'Updated'),
            'delivered' => Yii::t('app.f12.ecommerce', 'Delivered'),
            'total' => Yii::t('app.f12.ecommerce', 'Total cost'),
            'status' => Yii::t('app.f12.ecommerce', 'Order status'),
            'delivery_status' => Yii::t('app.f12.ecommerce', 'Delivery status'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\ecommerce\models\queries\OrderQuery(get_called_class());
    }
}
