<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\queries\EcCatego;
use floor12\ecommerce\models\queries\EcCategoryQuery;
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
 * @property EcItemParam[] $params
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
            'items_total' => Yii::t('app.f12.ecommerce', 'Total items'),
            'params_total' => Yii::t('app.f12.ecommerce', 'Total params'),
            'children_total' => Yii::t('app.f12.ecommerce', 'Children'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(EcItem::className(), ['id' => 'item_id'])
            ->viaTable('{{%ec_item_category}}', ['category_id' => 'id'])
            ->inverseOf('categories');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParams()
    {
        return $this->hasMany(EcItemParam::className(), ['id' => 'param_id'])
            ->viaTable('{{%ec_param_category}}', ['category_id' => 'id'])
            ->inverseOf('categories');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return EcCatego
     * Query the active query used by this AR class.
     */
    public static function find()
    {
        return new EcCategoryQuery(get_called_class());
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
}
