<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\queries\PaymentQuery;
use Yii;

/**
 * This is the model class for table "ec_payment".
 *
 * @property int $id
 * @property int $created Creation timestamp
 * @property int $updated Update timestamp
 * @property int $payed Payed timestamp
 * @property int $order_id Order id
 * @property int $status Payment status
 * @property int $type Payment type
 * @property int $external_id External payment system id
 * @property double $sum Sum
 * @property string $comment Payment comment
 *
 * @property Order $order
 */
class Payment extends \yii\db\ActiveRecord
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
            [['created', 'updated', 'payed', 'order_id', 'status', 'type', 'external_id'], 'integer'],
            [['sum'], 'number'],
            [['comment'], 'string'],
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
            'created' => Yii::t('app.f12.ecommerce', 'Creation timestamp'),
            'updated' => Yii::t('app.f12.ecommerce', 'Update timestamp'),
            'payed' => Yii::t('app.f12.ecommerce', 'Payed timestamp'),
            'order_id' => Yii::t('app.f12.ecommerce', 'Order id'),
            'status' => Yii::t('app.f12.ecommerce', 'Payment status'),
            'type' => Yii::t('app.f12.ecommerce', 'Payment type'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External payment system id'),
            'sum' => Yii::t('app.f12.ecommerce', 'Sum'),
            'comment' => Yii::t('app.f12.ecommerce', 'Payment comment'),
        ];
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
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }
}
