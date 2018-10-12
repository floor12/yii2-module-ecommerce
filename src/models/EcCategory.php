<?php

namespace floor12\ecommerce\models;

use Yii;

/**
 * This is the model class for table "ec_category".
 *
 * @property int $id
 * @property string $title Category title
 * @property int $parent_id Parent category
 * @property int $status Category status
 *
 * @property EcItemCategory[] $ecItemCategories
 * @property EcItemParam[] $ecItemParams
 */
class EcCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parent_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'title' => Yii::t('app.f12.ecommerce', 'Category title'),
            'parent_id' => Yii::t('app.f12.ecommerce', 'Parent category'),
            'status' => Yii::t('app.f12.ecommerce', 'Category status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcItemCategories()
    {
        return $this->hasMany(EcItemCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcItemParams()
    {
        return $this->hasMany(EcItemParam::className(), ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\EcCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\ecommerce\models\queries\EcCategoryQuery(get_called_class());
    }
}
