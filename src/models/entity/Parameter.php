<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\CategoryQuery;
use floor12\ecommerce\models\query\ParameterQuery;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "parameter".
 *
 * @property int $id
 * @property string $title Parameter title
 * @property string|null $unit Unit of measure
 * @property int $type_id Parameter type
 * @property string|null $external_id Extermnl id
 * @property int $hide Hide on site
 *
 * @property ParameterValue[] $parameterValues
 */
class Parameter extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_parameter';
    }

    /**
     * {@inheritdoc}
     * @return ParameterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParameterQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['type_id', 'hide'], 'integer'],
            [['title', 'unit', 'external_id'], 'string', 'max' => 255],
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
            'unit' => Yii::t('app.f12.ecommerce', 'Unit of measure'),
            'type_id' => Yii::t('app.f12.ecommerce', 'Parameter type'),
            'category_ids' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'categories_total' => Yii::t('app.f12.ecommerce', 'Categories total'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
            'hide' => Yii::t('app.f12.ecommerce', 'Hide on website'),
        ];
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
            'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
            ],
        ];
    }

    /**
     * @return CategoryQuery
     * @throws InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('{{ec_parameter_category}}', ['parameter_id' => 'id'])
            ->inverseOf('parameters');
    }

    /**
     * @return ActiveQuery
     */
    public function getParameterValues()
    {
        return $this->hasMany(ParameterValue::class, ['parameter_id' => 'id'])->orderBy('sort');
    }
}
