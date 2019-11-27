<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\DiscountGroupQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "discount_group".
 *
 * @property int $id
 * @property int $created_at Created at
 * @property int $created_by Created by
 * @property int $updated_at Updated at
 * @property int $updated_by Updated by
 * @property string $title Group title
 * @property string $description Discount description
 * @property int $status Status
 * @property int|null $discount_price_id Discount item price id
 * @property int|null $discount_percent Discount in percents
 * @property int|null $item_quantity Quantity of items of this group
 *
 */
class DiscountGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_discount_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'title', 'description', 'status'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'status', 'discount_price_id', 'discount_percent', 'item_quantity'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'created_at' => Yii::t('app.f12.ecommerce', 'Created At'),
            'created_by' => Yii::t('app.f12.ecommerce', 'Created By'),
            'updated_at' => Yii::t('app.f12.ecommerce', 'Updated At'),
            'updated_by' => Yii::t('app.f12.ecommerce', 'Updated By'),
            'title' => Yii::t('app.f12.ecommerce', 'Title'),
            'description' => Yii::t('app.f12.ecommerce', 'Description'),
            'status' => Yii::t('app.f12.ecommerce', 'Disable'),
            'discount_price_id' => Yii::t('app.f12.ecommerce', 'Discount Price ID'),
            'discount_percent' => Yii::t('app.f12.ecommerce', 'Discount Percent'),
            'item_quantity' => Yii::t('app.f12.ecommerce', 'Product Quantity'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DiscountGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DiscountGroupQuery(get_called_class());
    }
}
