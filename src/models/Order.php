<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\enum\DeliveryType;
use floor12\ecommerce\models\queries\OrderQuery;
use floor12\phone\PhoneValidator;
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
 * @property int $delivery_status Delivery status
 * @property string $external_id Extermnl indificator
 * @property int $delivery_type_id Delivery type
 * @property string $fullname Fullname
 * @property string $phone Phone
 * @property string $mail Email
 * @property string $address Address
 * @property string $comment Client comment
 * @property string $comment_admin Admin comment
 *
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{

    const SCENARIO_CHECKOUT = 'checkout';
    const SCENARIO_ADMIN = 'admin';

    public $cart;

    public $postcode;
    public $city;
    public $street;
    public $building;
    public $apartament;

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
            [['address', 'fullname'], 'string', 'max' => 255],
            [['delivery_type_id'], 'integer'],
            ['phone', PhoneValidator::class],
            ['email', 'email'],

            [['fullname', 'delivery_type_id', 'email', 'phone'], 'required', 'on' => self::SCENARIO_CHECKOUT],
            [['postcode', 'city', 'street', 'building', 'apartament', 'address'], 'required',
                'on' => self::SCENARIO_CHECKOUT,
                'message' => Yii::t('app.f12.ecommerce', 'Please fill this field.'),
                'when' => function (self $model) {
                    return $model->delivery_type_id != DeliveryType::PICK_UP;
                }],
            ['postcode', 'match', 'pattern' => '/[0-9]{5,6}/'],
            [['comment_admin'], 'string', 'on' => self::SCENARIO_ADMIN],
            [['external_id'], 'string', 'on' => self::SCENARIO_ADMIN],
            [['status', 'delivery_status'], 'integer', 'on' => self::SCENARIO_ADMIN],
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
            'external_id' => Yii::t('app.f12.ecommerce', 'Extermnl indificator'),
            'delivery_type_id' => Yii::t('app.f12.ecommerce', 'Delivery type'),
            'fullname' => Yii::t('app.f12.ecommerce', 'Fullname'),
            'phone' => Yii::t('app.f12.ecommerce', 'Phone'),
            'email' => Yii::t('app.f12.ecommerce', 'Email'),
            'address' => Yii::t('app.f12.ecommerce', 'Address'),
            'comment' => Yii::t('app.f12.ecommerce', 'Client comment'),
            'comment_admin' => Yii::t('app.f12.ecommerce', 'Admin comment'),
            'postcode' => Yii::t('app.f12.ecommerce', 'Postcode'),
            'city' => Yii::t('app.f12.ecommerce', 'City'),
            'street' => Yii::t('app.f12.ecommerce', 'Street name'),
            'building' => Yii::t('app.f12.ecommerce', 'Building number'),
            'apartament' => Yii::t('app.f12.ecommerce', 'Apartament or office number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
