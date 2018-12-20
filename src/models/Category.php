<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\logic\CategoryPathBuilder;
use floor12\ecommerce\models\queries\CategoryQuery;
use floor12\ecommerce\models\queries\EcCatego;
use floor12\ecommerce\models\queries\ItemParamQuery;
use voskobovich\linker\LinkerBehavior;
use Yii;

/**
 * This is the model class for table "ec_category".
 *
 * @property int $id
 * @property string $title Category title
 * @property string $path Category title with path
 * @property int $parent_id Parent category
 * @property int $status Category status
 * @property string $external_id External id
 *
 * @property Category[] $children
 * @property Category $parent
 * @property ItemParam[] $params
 * @property ItemParam[] $checkbox_params
 * @property ItemParam[] $slider_params
 */
class Category extends \yii\db\ActiveRecord
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
            [['title', 'external_id'], 'string', 'max' => 255],
            ['param_ids', 'each', 'rule' => ['integer']]
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
            'param_ids' => Yii::t('app.f12.ecommerce', 'Linked parameters'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::class, ['id' => 'item_id'])
            ->viaTable('{{%ec_item_category}}', ['category_id' => 'id'])
            ->inverseOf('categories');
    }

    /**
     * @return ItemParamQuery
     */
    public function getParams()
    {
        return $this->hasMany(ItemParam::class, ['id' => 'param_id'])
            ->viaTable('{{%ec_param_category}}', ['category_id' => 'id'])
            ->orderBy('type_id')
            ->inverseOf('categories');
    }

//    /**
//     * @return ItemParamQuery
//     */
    public function getParamsWithParent()
    {
        $category_ids = Category::find()->withParents($this)->select('id')->column();

        return ItemParam::find()
            ->byCategoyIds($category_ids)
            ->orderBy('type_id');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckbox_params()
    {
        return $this->getParamsWithParent()->checkbox();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlider_params()
    {
        return $this->getParamsWithParent()->slider();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * {@inheritdoc}
     * @return EcCatego
     * Query the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @return int
     */
    public function getItems_total()
    {
        return (int)$this->getItems()->count();
    }

    /**
     * @return int
     */
    public function getParams_total()
    {
        return (int)$this->getParams()->count();
    }

    /**
     * @return int
     */
    public function getChildren_total()
    {
        return (int)$this->getChildren()->count();
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
                    'param_ids' => 'params',
                ],
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        $pathBuilder = new CategoryPathBuilder;
        $pathBuilder->execute();
        $pathBuilder->execute();
        return parent::afterSave($insert, $changedAttributes);
    }
    
}
