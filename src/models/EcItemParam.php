<?php

namespace floor12\ecommerce\models;

use Yii;

/**
 * This is the model class for table "ec_item_param".
 *
 * @property int $id
 * @property string $title Parameter title
 * @property string $unit Parameter unit of measure
 * @property int $type_id Parameter type
 * @property int $category_id Category link
 *
 * @property EcCategory $category
 * @property EcItemParamValue[] $ecItemParamValues
 */
class EcItemParam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_item_param';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['type_id', 'category_id'], 'integer'],
            [['title', 'unit'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => EcCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'title' => Yii::t('app.f12.ecommerce', 'Parameter title'),
            'unit' => Yii::t('app.f12.ecommerce', 'Parameter unit of measure'),
            'type_id' => Yii::t('app.f12.ecommerce', 'Parameter type'),
            'category_id' => Yii::t('app.f12.ecommerce', 'Category link'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(EcCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcItemParamValues()
    {
        return $this->hasMany(EcItemParamValue::className(), ['param_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\EcItemParamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\ecommerce\models\queries\EcItemParamQuery(get_called_class());
    }
}
