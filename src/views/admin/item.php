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
use floor12\ecommerce\models\EcItem;
use floor12\editmodal\EditModalHelper;
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

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        'title',
        [
            'attribute' => 'price',
            'content' => function (EcItem $model) {
                return Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency);
            },
        ], [
            'attribute' => 'price_discount',
            'content' => function (EcItem $model) {
                return Yii::$app->formatter->asCurrency($model->price_discount, Yii::$app->getModule('shop')->currency);
            },
        ],
        [
            'header' => Yii::t('app.f12.ecommerce', 'Linked categories'),
            'content' => function (EcItem $model) {
                return implode(array_map(function ($model) {
                    return Html::tag('span', $model->title, ['class' => 'tag']);
                }, $model->categories));
            },
        ],
        'availible',
        [
            'contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (EcItem $model) {
                return
                    Html::a(FontAwesome::icon('list'), NULL, ['onclick' => EditModalHelper::showForm('shop/admin/item-params', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(FontAwesome::icon('pencil'), NULL, ['onclick' => EditModalHelper::showForm('shop/admin/item-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(FontAwesome::icon('trash'), NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/item-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

