<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\StockQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ec_stock".
 *
 * @property int $id
 * @property string $title Stock title
 * @property int $status Hide
 * @property string|null $description Stock description
 * @property string|null $external_id External ID
 * @property string|null $url Stock url
 * @property string|null $title_public Public title
 * @property int $sort
 *
 * @property StockBalance[] $stockBalances
 */
class Stock extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_stock';
    }

    /**
     * {@inheritdoc}
     * @return StockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StockQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
            [['description','address'], 'string'],
            [['title', 'external_id', 'url', 'title_public'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'title' => Yii::t('app.f12.ecommerce', 'Stock title'),
            'status' => Yii::t('app.f12.ecommerce', 'Hide'),
            'description' => Yii::t('app.f12.ecommerce', 'Stock description'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External ID'),
            'url' => Yii::t('app.f12.ecommerce', 'Stock url'),
            'title_public' => Yii::t('app.f12.ecommerce', 'Public title'),
            'address' => Yii::t('app.f12.ecommerce', 'Stock address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockBalances()
    {
        return $this->hasMany(StockBalance::class, ['stock_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
            ],
        ];
    }
}
