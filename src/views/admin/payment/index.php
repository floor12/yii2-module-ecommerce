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
use floor12\ecommerce\models\entity\Payment;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\editmodal\EditModalColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Payments');
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
        [
            'attribute' => 'status',
            'content' => function (Payment $model) {
                return PaymentStatus::getLabel($model->status);
            }
        ],
        [
            'attribute' => 'type',
            'content' => function (Payment $model) {
                return \floor12\ecommerce\models\enum\PaymentType::getLabel($model->type);
            }
        ],
        'order.fullname',
        'order.email:email',
        'sum',
        [
            'class' => EditModalColumn::class,
            'editPath' => '/shop/admin/payment/form',
            'deletePath' => '/shop/admin/payment/delete',
        ],
    ]
]);

Pjax::end();

