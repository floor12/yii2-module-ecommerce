<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 10:02
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\OrderFilter
 */

use floor12\ecommerce\assets\EcommerceAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\Order;
use floor12\editmodal\EditModalHelper;
use floor12\phone\PhoneFormatter;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Orders');

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::tag('br');

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        'created:datetime',
        'fullname',
        [
            'attribute' => 'phone',
            'content' => function (Order $model) {
                return PhoneFormatter::run($model->phone);
            },
        ],
        'email:email',
        [
            'attribute' => 'total',
            'content' => function (Order $model) {
                $ret = Html::tag('div', \floor12\ecommerce\assets\IconHelper::BOXES . ' ' . Yii::$app->formatter->asCurrency($model->items_cost, Yii::$app->getModule('shop')->currency), ['class' => 'small']);
                $ret .= Html::tag('div', \floor12\ecommerce\assets\IconHelper::DELIVERY . ' ' . Yii::$app->formatter->asCurrency($model->delivery_cost, Yii::$app->getModule('shop')->currency), ['class' => 'small']);
                $ret .= Html::tag('div', \floor12\ecommerce\assets\IconHelper::FLAG . ' ' . Yii::$app->formatter->asCurrency($model->total, Yii::$app->getModule('shop')->currency), ['class' => 'small']);
                return $ret;
            },
        ],
        [
            'attribute' => 'status',
            'content' => function (Order $model) {
                return OrderStatus::getLabel($model->status);
            },
        ],
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (Order $model) {
                return
                    Html::a(\floor12\editmodal\IconHelper::PENCIL, NULL, ['onclick' => EditModalHelper::showForm('shop/admin/order-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(\floor12\editmodal\IconHelper::TRASH, NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/order-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

