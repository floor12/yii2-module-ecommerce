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
use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\editmodal\EditModalColumn;
use floor12\phone\PhoneFormatter;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Orders');
$this->params['breadcrumbs'][] = Yii::t('app.f12.ecommerce', 'Store administration');
$this->params['breadcrumbs'][] = $this->title;

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
                $ret = Html::tag('div', \floor12\ecommerce\assets\IconHelper::BOXES . ' ' . Yii::$app->formatter->asCurrency($model->products_cost,
                        Yii::$app->getModule('shop')->currency), ['class' => 'small']);
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
        [
            'class' => EditModalColumn::class,
            'editPath' => '/shop/admin/order/form',
            'deletePath' => '/shop/admin/order/delete',
        ],
    ]
]);

Pjax::end();

