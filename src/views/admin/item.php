<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 10:02
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\ItemFilter
 */

use floor12\ecommerce\assets\EcommerceAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\Item;
use floor12\editmodal\EditModalHelper;
use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Items');

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(FontAwesome::icon('plus') . " " . Yii::t('app.f12.ecommerce', 'Create item'), null, [
    'onclick' => EditModalHelper::showForm('shop/admin/item-form', 0),
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
            <div class="col-md-6">
                <?= $form->field($model, 'filter')
                    ->label(false)
                    ->textInput(['placeholder' => Yii::t('app.f12.ecommerce', 'filter items')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')
                    ->label(false)
                    ->dropDownList(Status::listData(), ['prompt' => Yii::t('app.f12.ecommerce', 'any status')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'hideOptions')->checkbox() ?>
            </div>
        </div>
    </div>
<?php

ActiveForm::end();

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'rowOptions' => function (Item $model) {
        if ($model->status == Status::DISABLED)
            return ['class' => 'disabled'];
    },
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        [
            'attribute' => 'title',
            'content' => function (Item $model) {
                return $model->title . Html::tag('div', $model->article, ['class' => 'small']);

            },
        ],
        [
            'attribute' => 'price',
            'content' => function (Item $model) {
                return Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency);
            },
        ],
        [
            'attribute' => 'price_discount',
            'content' => function (Item $model) {
                return Yii::$app->formatter->asCurrency($model->price_discount, Yii::$app->getModule('shop')->currency);
            },
        ],
        [
            'header' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'content' => function (Item $model) {
                return implode(array_map(function ($model) {
                    return Html::tag('span', $model->title, ['class' => 'tag']);
                }, $model->categories));
            },
        ],
        'available',
        [
            'contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (Item $model) {
                $html = '';
                if (!$model->parent_id) {
                    $html .= Html::a(FontAwesome::icon('plus'), NULL, ['title' => Yii::t('app.f12.ecommerce', 'Add option'), 'onclick' => EditModalHelper::showForm('shop/admin/item-option', ['parent_id' => $model->id]), 'class' => 'btn btn-default btn-sm']) . " ";
                }
                $html .= Html::a(FontAwesome::icon('list'), NULL, ['title' => Yii::t('app.f12.ecommerce', 'Update params'), 'onclick' => EditModalHelper::showForm('shop/admin/item-params', $model->id), 'class' => 'btn btn-default btn-sm']) . " ";
                $html .= Html::a(FontAwesome::icon('pencil'), NULL, ['title' => Yii::t('app.f12.ecommerce', 'Update'), 'onclick' => EditModalHelper::showForm('shop/admin/item-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " ";
                $html .= Html::a(FontAwesome::icon('trash'), NULL, ['title' => Yii::t('app.f12.ecommerce', 'Delete'), 'onclick' => EditModalHelper::deleteItem('shop/admin/item-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
                return $html;
            },
        ]
    ]
]);

Pjax::end();

