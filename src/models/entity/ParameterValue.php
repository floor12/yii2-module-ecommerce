<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\ParameterValueQuery;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "parameter_value".
 *
 * @property int $id
 * @property string $value Parameter value
 * @property string|null $unit Parameter unit of measure
 * @property int $parameter_id Parameter id
 * @property int|null $sort Sort position
 * @property string|null $color_hex Value color for frontend
 *
 * @property Parameter $parameter
 * @property ProductVariation[] $productVariations
 */
class ParameterValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_parameter_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'parameter_id'], 'required'],
            [['parameter_id', 'sort'], 'integer'],
            [['value', 'unit'], 'string', 'max' => 255],
            [['color_hex'], 'string', 'max' => 7],
            [['parameter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::class, 'targetAttribute' => ['parameter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'value' => Yii::t('app.f12.ecommerce', 'Value'),
            'unit' => Yii::t('app.f12.ecommerce', 'Unit of measure'),
            'parameter_id' => Yii::t('app.f12.ecommerce', 'Parameter'),
            'sort' => Yii::t('app.f12.ecommerce', 'Order'),
            'color_hex' => Yii::t('app.f12.ecommerce', 'Color'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
                'sortConfig' => [
                    'condition' => function ($query, $model) {
                        $query->andWhere(['parameter_id' => $model->parameter_id]);
                    },
                ]
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getParameter()
    {
        return $this->hasOne(Parameter::class, ['id' => 'parameter_id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getProductVariations()
    {
        return $this->hasMany(ProductVariation::class, ['id' => 'product_variation_id'])->viaTable('parameter_value_product_variation', ['parameter_value_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ParameterValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParameterValueQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return trim("{$this->parameter->title}: {$this->value} {$this->unit}");
    }
}
