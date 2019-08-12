<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 10:02
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\ParamFilter
 */

use floor12\ecommerce\assets\EcommerceAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\EcParam;
use floor12\ecommerce\models\ItemParam;
use floor12\editmodal\EditModalHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Item parameters');


echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(\floor12\editmodal\IconHelper::PENCIL . " " . Yii::t('app.f12.ecommerce', 'Create parameter'), null, [
    'onclick' => EditModalHelper::showForm('shop/admin/param-form', 0),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'rowOptions' => function (ItemParam $model) {
        if ($model->hide)
            return ['class' => 'disabled'];
    },
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        'title',
        'categories_total',
        'unit',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (ItemParam $model) {
                return
                    Html::a(\floor12\editmodal\IconHelper::PENCIL, NULL, ['onclick' => EditModalHelper::showForm('shop/admin/param-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(\floor12\editmodal\IconHelper::TRASH, NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/param-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

