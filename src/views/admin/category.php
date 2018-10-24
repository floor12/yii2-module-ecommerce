<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 10:02
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\CategoryFilter
 */

use floor12\ecommerce\assets\EcommerceAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\enum\Status;
use floor12\editmodal\EditModalHelper;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Categories');

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(FontAwesome::icon('plus') . " " . Yii::t('app.f12.ecommerce', 'Create category'), null, [
    'onclick' => EditModalHelper::showForm('shop/admin/category-form', 0),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'rowOptions' => function (EcCategory $model) {
        if ($model->status == Status::DISABLED)
            return ['class' => 'disabled'];
    },
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        'title',
        'items_total',
        'params_total',
        'children_total',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (EcCategory $model) {
                return
                    Html::a(FontAwesome::icon('pencil'), NULL, ['onclick' => EditModalHelper::showForm('shop/admin/category-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(FontAwesome::icon('trash'), NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/category-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

