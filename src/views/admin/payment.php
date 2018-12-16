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
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\Payment;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Payments');

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
            'attribute' => 'type_id',
            'content' => function (Payment $model) {
                return \floor12\ecommerce\models\enum\PaymentType::getLabel($model->type);
            }
        ],
        'order.fullname',
        'order.email:email',
        'sum',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (Payment $model) {
                return;
                // Html::a(FontAwesome::icon('eye-open'), NULL, ['onclick' => EditModalHelper::showForm('shop/admin/payment-form', $model->id), 'class' => 'btn btn-default btn-sm']);
                //   Html::a(FontAwesome::icon('trash'), NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/order-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

