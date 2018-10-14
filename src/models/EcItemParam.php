<?php

namespace floor12\ecommerce\models;

use Yii;
use \floor12\ecommerce\models\queries\EcItemParamQuery;
use voskobovich\linker\LinkerBehavior;

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
            [['type_id'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(EcCategory::className(), ['id' => 'category_id'])
            ->viaTable('{{ec_param_category}}', ['param_id' => 'id'])
            ->inverseOf('params');
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
     * @return EcItemParamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EcItemParamQuery(get_called_class());
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
