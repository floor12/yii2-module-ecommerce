<?php

namespace floor12\ecommerce\models\entity;

use floor12\ecommerce\models\query\ProductQuery;
use floor12\files\components\FileBehaviour;
use floor12\files\models\File;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title Item title
 * @property string|null $subtitle Item subtitle
 * @property string|null $description Item description
 * @property string|null $seo_description Description META
 * @property string|null $seo_title Page title
 * @property int $status Item status
 * @property string|null $external_id Extermnl indificator
 * @property string|null $article Item article
 * @property float $weight_delivery Weight for delivery
 *
 * @property ProductVariation[] $variations
 * @property Category[] $categories
 * @property array $category_ids
 * @property File[] $images
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_product';
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['weight_delivery'], 'number'],
            [['title', 'subtitle', 'seo_description', 'seo_title', 'external_id', 'article'], 'string', 'max' => 255],
            [['category_ids'], 'each', 'rule' => ['integer']],
            ['images', 'file', 'maxFiles' => 100, 'extensions' => ['jpg', 'jpeg', 'png', 'webp']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'title' => Yii::t('app.f12.ecommerce', 'Product title'),
            'subtitle' => Yii::t('app.f12.ecommerce', 'Product subtitle'),
            'description' => Yii::t('app.f12.ecommerce', 'Product description'),
            'seo_description' => Yii::t('app.f12.ecommerce', 'Description META'),
            'seo_title' => Yii::t('app.f12.ecommerce', 'Page title'),
            'external_id' => Yii::t('app.f12.ecommerce', 'External indificator'),
            'article' => Yii::t('app.f12.ecommerce', 'Product article'),
            'weight_delivery' => Yii::t('app.f12.ecommerce', 'Product weight for delivery'),
            'price' => Yii::t('app.f12.ecommerce', 'Price'),
            'category_ids' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'variations' => Yii::t('app.f12.ecommerce', 'Product variations'),
            'status' => Yii::t('app.f12.ecommerce', 'Disable product'),
            'images' => Yii::t('app.f12.ecommerce', 'Product images'),
        ];
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
                    'category_ids' => 'categories'
                ],
            ],
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('{{ec_product_category}}', ['product_id' => 'id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getDiscounts()
//    {
//     //  return $this->hasMany(DiscountGroup::class, ['id' => 'discount_group_id'])
//       //     ->viaTable('{{ec_discount_group_product}}', ['product_id' => 'id']);
//    }


    public function getPrice()
    {
        return $this->getVariations()->select('price_0')->scalar();
    }

    /**
     * @return ActiveQuery
     */
    public function getVariations()
    {
        return $this->hasMany(ProductVariation::class, ['product_id' => 'id']);
    }

    public function getPriceOld()
    {
        return $this->getVariations()->select('price_old')->scalar();
    }

    /**
     * @param int $parameter_id
     */
    public function getParameterValues(int $parameter_id)
    {
        $sum = new Expression('SUM(ec_item.available) as total');
        $ids = $this->getVariations()->select('id')->column();
        return ParameterValue::find()
            ->distinct()
            ->byParameterId($parameter_id)
            ->byProductVariations($ids)
            ->select('value')
            ->orderBy('sort')
            ->asArray()
            ->all();
    }
}
