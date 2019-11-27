<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 10:02
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\ProductFilter
 */

use floor12\ecommerce\assets\EcommerceAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\Status;
use floor12\editmodal\EditModalHelper;
use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Products');
$this->params['breadcrumbs'][] = Yii::t('app.f12.ecommerce', 'Store administration');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(floor12\editmodal\IconHelper::PLUS . " " . Yii::t('app.f12.ecommerce', 'Create product'), null, [
    'onclick' => EditModalHelper::showForm('/shop/admin/product/form'),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

$form = ActiveForm::begin([
    'method' => 'GET',
    'id' => 'f12-eccomerce-item-filter',
    'options' => ['data-container' => '#items'],
]);
?>

    <div class="item-filter">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'filter')
                    ->label(false)
                    ->textInput(['placeholder' => Yii::t('app.f12.ecommerce', 'find product')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'category_id')
                    ->label(false)
                    ->dropDownList(Category::find()->dropdown(), ['prompt' => Yii::t('app.f12.ecommerce', 'all categories')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')
                    ->label(false)
                    ->dropDownList(Status::listData(), ['prompt' => Yii::t('app.f12.ecommerce', 'any status')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'withoutExternal')->checkbox() ?>
            </div>
        </div>
    </div>
<?php

ActiveForm::end();

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'rowOptions' => function (Product $model) {
        if ($model->status == Status::DISABLED)
            return ['class' => 'disabled'];
    },
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        [
            'contentOptions' => ['class' => 'f12-ec-image-cell'],
            'content' => function (Product $model) {
                if (!empty($model->images))
                    return Html::img($model->images[0]->getPreviewWebPath(150));
            }
        ],
        [
            'attribute' => 'title',
            'content' => function (Product $model) {
                $categories = implode(array_map(function ($model) {
                    return Html::tag('span', $model->title, ['class' => 'tag']);
                }, $model->categories));

                return "<b>{$model->title}</b> <div class='small'>{$model->article}</div> <div>{$categories}</div><div>id: <b>{$model->id}</b></div>";
            },
        ],

        [
            'contentOptions' => ['style' => 'min-width:170px; text-align:right;'],
            'content' => function (Product $model) {
                $html = '';
                $html .= Html::a(floor12\editmodal\IconHelper::PLUS, NULL, ['title' => Yii::t('app.f12.ecommerce', 'Create varioation'), 'onclick' =>
                        EditModalHelper::showForm('/shop/admin/product-variation/form', ['product_id' => $model->id]), 'class' => 'btn btn-default 
                        btn-sm']) . " ";
                $html .= Html::a(floor12\editmodal\IconHelper::PENCIL, NULL, ['title' => Yii::t('app.f12.ecommerce', 'Update'), 'onclick' => EditModalHelper::showForm('/shop/admin/product/form', $model->id), 'class' => 'btn btn-default btn-sm']) . " ";
                $html .= Html::a(floor12\editmodal\IconHelper::TRASH, NULL, ['title' => Yii::t('app.f12.ecommerce', 'Delete'), 'onclick'
                => EditModalHelper::deleteItem('/shop/admin/product/delete', $model->id), 'class' => 'btn btn-default btn-sm']);
                return $html;
            },
        ],
        [
            'attribute' => 'variations',
            'contentOptions' => ['class' => 'f12-product-variation-cell'],
            'content' => function (Product $model) {
                return \floor12\ecommerce\components\ProductVariationsWidget::widget(['model' => $model]);
            },
        ],
    ]
]);

Pjax::end();

