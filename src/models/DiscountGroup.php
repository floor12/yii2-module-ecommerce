<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\logic\ParamProcessor;
use floor12\ecommerce\models\queries\DiscountGroupQuery;
use Yii;

/**
 * This is the model class for table "ec_discount_group".
 *
 * @property int $id
 * @property int $created_at Created at
 * @property int $created_by Created by
 * @property int $updated_at Updated at
 * @property int $updated_by Updated by
 * @property string $title Group title
 * @property string $description Discount description
 * @property int $status Status
 * @property int $discount_price_id Discount item price id
 * @property int $discount_percent Discount in percents
 * @property int $item_quantity Quantity of items of this group
 *
 * @property EcDiscountGroupItem[] $ecDiscountGroupItems
 */
class DiscountGroup extends \yii\db\ActiveRecord
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
     * @return DiscountGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DiscountGroupQuery(get_called_class());
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
            'item_quantity' => Yii::t('app.f12.ecommerce', 'Item Quantity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcDiscountGroupItems()
    {
        return $this->hasMany(EcDiscountGroupItem::className(), ['discount_group_id' => 'id']);
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
