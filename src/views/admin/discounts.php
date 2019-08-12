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
use floor12\ecommerce\models\DiscountGroup;
use floor12\ecommerce\models\EcParam;
use floor12\ecommerce\models\enum\PriceType;
use floor12\editmodal\EditModalHelper;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Discount groups');


echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(\floor12\editmodal\IconHelper::PLUS . " " . Yii::t('app.f12.ecommerce', 'Create group'), null, [
    'onclick' => EditModalHelper::showForm('shop/admin/discount-form', 0),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped'],
    'rowOptions' => function (DiscountGroup $model) {
        if ($model->status)
            return ['class' => 'disabled'];
    },
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        'title',
        [
            'attribute' => 'discount_price_id',
            'content' => function (DiscountGroup $model) {
                return PriceType::getLabel($model->discount_price_id);
            }
        ],
        'item_quantity',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (DiscountGroup $model) {
                return
                    Html::a(\floor12\editmodal\IconHelper::PENCIL, NULL, ['onclick' => EditModalHelper::showForm('shop/admin/discount-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                    Html::a(\floor12\editmodal\IconHelper::TRASH, NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/discount-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
            },
        ]
    ]
]);

Pjax::end();

