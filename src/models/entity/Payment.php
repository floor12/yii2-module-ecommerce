<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\PaymentQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $created Creation timestamp
 * @property int $updated Update timestamp
 * @property int|null $payed Payed timestamp
 * @property int $order_id Order id
 * @property int $status Payment status
 * @property int $type Payment type
 * @property string|null $external_id
 * @property float $sum Sum
 * @property string|null $comment Payment comment
 * @property string|null $form_url Payment form address
 * @property int|null $external_status Payment status in external service
 *
 * @property Order $order
 */
class Payment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'updated', 'order_id', 'status', 'type', 'sum'], 'required'],
            [['created', 'updated', 'payed', 'order_id', 'status', 'type', 'external_status'], 'integer'],
            [['sum'], 'number'],
            [['comment'], 'string'],
            [['external_id'], 'string', 'max' => 255],
            [['form_url'], 'string', 'max' => 512],
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
            'created' => Yii::t('app.f12.ecommerce', 'Creation timestamp'),
            'updated' => Yii::t('app.f12.ecommerce', 'Update timestamp'),
            'payed' => Yii::t('app.f12.ecommerce', 'Payed timestamp'),
            'order_id' => Yii::t('app.f12.ecommerce', 'Order id'),
            'status' => Yii::t('app.f12.ecommerce', 'Payment status'),
            'type' => Yii::t('app.f12.ecommerce', 'Payment type'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External ID'),
            'sum' => Yii::t('app.f12.ecommerce', 'Sum'),
            'comment' => Yii::t('app.f12.ecommerce', 'Payment comment'),
            'form_url' => Yii::t('app.f12.ecommerce', 'Payment form address'),
            'external_status' => Yii::t('app.f12.ecommerce', 'Payment status in external service'),
        ];
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
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }
}
