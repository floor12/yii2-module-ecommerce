<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\forms\CartForm;
use floor12\ecommerce\models\query\OrderQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use floor12\phone\PhoneValidator;
use floor12\ecommerce\models\enum\DeliveryType;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int|null $user_id Buyer indificator
 * @property int $created Created
 * @property int $updated Updated
 * @property int|null $delivered Delivered
 * @property float $total Total cost
 * @property int $status Order status
 * @property int $delivery_status Delivery status
 * @property string|null $external_id Extermnl indificator
 * @property int $delivery_type_id Delivery type
 * @property string|null $fullname Fullname
 * @property string|null $phone Phone
 * @property string|null $email Email
 * @property string|null $address Address
 * @property string|null $comment Client comment
 * @property string|null $comment_admin Admin comment
 * @property int|null $city_id City ID for delivery service
 * @property float $delivery_cost Delivery cost
 * @property float $products_cost All items cost
 * @property float $products_weight All items weight
 * @property int|null $payment_type_id Payment type
 *
 * @property OrderItem[] $orderItems
 * @property Payment[] $payments
 */
class Order extends ActiveRecord
{
    const SCENARIO_CHECKOUT = 'checkout';
    const SCENARIO_ADMIN = 'admin';
    /**
     * @var CartForm
     */
    public $cart;
    /**
     * @var string
     */
    public $postcode;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $street;
    /**
     * @var
     */
    public $building;
    /**
     * @var
     */
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
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
//        return [
//            [['user_id', 'created', 'updated', 'delivered', 'status', 'delivery_status', 'delivery_type_id', 'city_id', 'payment_type_id'], 'integer'],
//            [['created', 'updated', 'delivery_type_id'], 'required'],
//            [['total', 'delivery_cost', 'products_cost', 'products_weight'], 'number'],
//            [['address', 'comment', 'comment_admin'], 'string'],
//            [['external_id', 'fullname', 'phone', 'email'], 'string', 'max' => 255],
//        ];

        return [
            [['address', 'fullname'], 'string', 'max' => 255],
            [['delivery_type_id', 'postcode'], 'integer'],
            ['phone', PhoneValidator::class],
            ['email', 'email'],
            [['fullname', 'delivery_type_id', 'email', 'phone', 'payment_type_id'], 'required', 'on' => self::SCENARIO_CHECKOUT],
            [['postcode', 'city', 'street', 'building', 'apartament', 'address', 'city_id'], 'required',
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
            'city_id' => Yii::t('app.f12.ecommerce', 'City ID for delivery service'),
            'delivery_cost' => Yii::t('app.f12.ecommerce', 'Delivery cost'),
            'products_cost' => Yii::t('app.f12.ecommerce', 'All items cost'),
            'products_weight' => Yii::t('app.f12.ecommerce', 'All items weight'),
            'payment_type_id' => Yii::t('app.f12.ecommerce', 'Payment type'),

            'postcode' => Yii::t('app.f12.ecommerce', 'Postcode'),
            'city' => Yii::t('app.f12.ecommerce', 'City'),
            'street' => Yii::t('app.f12.ecommerce', 'Street name'),
            'building' => Yii::t('app.f12.ecommerce', 'Building number'),
            'apartament' => Yii::t('app.f12.ecommerce', 'Apartament or office number'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['order_id' => 'id']);
    }
}
