<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\queries\ItemQuery;
use floor12\files\components\FileBehaviour;
use floor12\files\models\File;
use floor12\pages\PageObjectInterface;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "ec_item".
 *
 * @property int $id
 * @property string $title Item title
 * @property string $subtitle Item subtitle
 * @property string $description Item description
 * @property string $seo_description Description META
 * @property string $seo_title Page title
 * @property double $price Price
 * @property double $price_current Price
 * @property double $price_discount Discount price
 * @property integer $available Available quantity
 * @property string $external_id External id
 * @property int $status Item status
 * @property int $parent_id Parent intem ID
 * @property string $url item view url
 * @property string $article Item article
 * @property string $weight_delivery Item weight for delivery
 *
 * @property ItemParamValue[] $itemParamValues
 * @property OrderItem[] $orderItems
 * @property Category[] $categories
 * @property array $category_ids
 * @property File[] $images
 * @property self $parent
 * @property self[] $options
 */
class Item extends ActiveRecord implements PageObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['price', 'price_discount', 'weight_delivery'], 'number'],
            [['status', 'available'], 'integer'],
            ['description', 'string'],
            [['title', 'subtitle', 'seo_description', 'seo_title', 'external_id', 'article'], 'string', 'max' => 255],
            [['category_ids'], 'each', 'rule' => ['integer']],
            ['images', 'file', 'maxFiles' => 100, 'extensions' => ['jpg', 'jpeg', 'png']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'title' => Yii::t('app.f12.ecommerce', 'Item title'),
            'subtitle' => Yii::t('app.f12.ecommerce', 'Item subtitle'),
            'description' => Yii::t('app.f12.ecommerce', 'Item description'),
            'seo_description' => Yii::t('app.f12.ecommerce', 'Description META'),
            'seo_title' => Yii::t('app.f12.ecommerce', 'Page title'),
            'price' => Yii::t('app.f12.ecommerce', 'Price'),
            'price_discount' => Yii::t('app.f12.ecommerce', 'Discount price'),
            'available' => Yii::t('app.f12.ecommerce', 'Available quantity'),
            'status' => Yii::t('app.f12.ecommerce', 'Disable item'),
            'category_ids' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'images' => Yii::t('app.f12.ecommerce', 'Item images'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
            'article' => Yii::t('app.f12.ecommerce', 'Item article'),
            'weight_delivery' => Yii::t('app.f12.ecommerce', 'Item weight for delivery'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemParamValues()
    {
        return $this->hasMany(ItemParamValue::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{ec_item_category}}', ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\ItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'files' => [
                'class' => FileBehaviour::class,
                'attributes' => ['images']
            ],
            'ManyToManyBehavior' => [
                'class' => LinkerBehavior::class,
                'relations' => [
                    'category_ids' => 'categories',
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::toRoute(['/shop/category/item', 'id' => $this->id]);
    }

    /** Этот метод мы добавляем исключительно чтобы иметь возможность делать жадную загрузку изображений
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(File::class, ['object_id' => 'id'])
            ->andWhere(['class' => self::class])
            ->orderBy('ordering');
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        if ($this->options)
            foreach ($this->options as $option)
                $option->delete();
        parent::afterDelete();
    }

    /**
     * @return float
     */
    public function getPrice_current()
    {
        return $this->price_discount ?: $this->price;
    }
}
