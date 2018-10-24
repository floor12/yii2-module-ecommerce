<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\queries\ItemParamQuery;
use voskobovich\linker\LinkerBehavior;
use Yii;

/**
 * This is the model class for table "ec_item_param".
 *
 * @property int $id
 * @property string $title Parameter title
 * @property string $unit Parameter unit of measure
 * @property int $type_id Parameter type
 * @property int $category_id Category link
 * @property string $external_id External id
 * @property string $hide Hide on website
 *
 * @property Category $category
 * @property ItemParamValue[] $ecItemParamValues
 */
class ItemParam extends \yii\db\ActiveRecord
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
            [['type_id', 'hide'], 'integer'],
            ['external_id', 'string'],
            [['title', 'unit'], 'string', 'max' => 255],
            [['category_ids'], 'each', 'rule' => ['integer']],
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
            'category_ids' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'categories_total' => Yii::t('app.f12.ecommerce', 'Categories total'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
            'hide' => Yii::t('app.f12.ecommerce', 'Hide on website'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{ec_param_category}}', ['param_id' => 'id'])
            ->inverseOf('params');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcItemParamValues()
    {
        return $this->hasMany(ItemParamValue::className(), ['param_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ItemParamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemParamQuery(get_called_class());
    }

    /**
     * @return int
     */
    public function getCategories_total()
    {
        return (int)$this->getCategories()->count();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ManyToManyBehavior' => [
                'class' => LinkerBehavior::class,
                'relations' => [
                    'category_ids' => 'categories',
                ],
            ],
        ];
    }
}
