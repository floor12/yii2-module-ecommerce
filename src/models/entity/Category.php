<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\CategoryQuery;
use floor12\ecommerce\models\query\ParameterQuery;
use floor12\ecommerce\models\query\ProductQuery;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title Category title
 * @property int|null $parent_id Parent category
 * @property int $status Category status
 * @property string|null $external_id External id
 * @property string|null $fulltitle Title with full path
 * @property int $sort Sort postion
 *
 * @property Parameter[] $parameters
 * @property array $parameter_ids
 * @property Category[] $categories
 * @property array $category_ids
 * @property Category $parent
 */
class Category extends ActiveRecord
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
     * @return CategoryQuery
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parent_id', 'status'], 'integer'],
            [['title', 'external_id', 'fulltitle'], 'string', 'max' => 255],
            ['parameter_ids', 'each', 'rule' => ['integer']],
            ['parent_id', 'default', 'value' => '0']
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
            'status' => Yii::t('app.f12.ecommerce', 'Disable category'),
            'items_total' => Yii::t('app.f12.ecommerce', 'Total items'),
            'params_total' => Yii::t('app.f12.ecommerce', 'Total params'),
            'children_total' => Yii::t('app.f12.ecommerce', 'Children'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
            'parameter_ids' => Yii::t('app.f12.ecommerce', 'Linked parameters'),
            'sort' => Yii::t('app.f12.ecommerce', 'Order'),
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
                    'parameter_ids' => 'parameters',
                ],
            ],
            'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
                'sortConfig' => [
                    'condition' => function ($query, $model) {
                        $query->andWhere(['parent_id' => $model->parent_id]);
                    },
                ]
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->fulltitle = $this->title;
        if ($this->parent_id)
            $this->fulltitle = "{$this->parent->fulltitle} / {$this->title}";
        return parent::beforeSave($insert);
    }

    /**
     * @return CategoryQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * @return CategoryQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * @return ProductQuery
     * @throws InvalidConfigException
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->viaTable('{{%ec_product_category}}', ['category_id' => 'id'])
            ->inverseOf('categories');
    }

    /**
     * @return ParameterQuery
     * @throws InvalidConfigException
     */
    public function getParameters()
    {
        return $this->hasMany(Parameter::class, ['id' => 'parameter_id'])
            ->viaTable('{{%ec_parameter_category}}', ['category_id' => 'id'])
            ->orderBy('type_id')
            ->inverseOf('categories');
    }
}
