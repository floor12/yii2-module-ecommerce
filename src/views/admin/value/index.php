<?php
/**
 * @var $model \floor12\ecommerce\models\filters\ParameterValueFilter
 */

use floor12\ecommerce\assets\EcommerceAdminAsset;
use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\entity\Parameter;
use floor12\editmodal\EditModalHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

EcommerceAdminAsset::register($this);

$this->title = Yii::t('app.f12.ecommerce', 'Parameters values');
$this->params['breadcrumbs'][] = Yii::t('app.f12.ecommerce', 'Store administration');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

//echo Html::a(\floor12\editmodal\IconHelper::PLUS . " " . Yii::t('app.f12.ecommerce', 'Create value'), null, [
//    'onclick' => EditModalHelper::showForm(['form'], 0),
//    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
//]);

echo Html::tag('br');

$form = ActiveForm::begin([
    'method' => 'GET',
    'options' => ['class' => 'autosubmit', 'data-container' => '#items'],
    'enableClientValidation' => false,
]); ?>
    <div class="filter-block">
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => 'Поиск', 'autofocus' => true]) ?>            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'parameter_id')
                    ->label(false)
                    ->dropDownList(Parameter::find()->dropdown(), ['prompt' => Yii::t('app.f12.ecommerce', 'parameter')]) ?>
            </div>
        </div>
    </div>

<?php

ActiveForm::end();


Pjax::begin([
    'id' => 'items',
    'scrollTo' => true,
]);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'layout' => '{items}{pager}{summary}',
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
        'id',
        'value',
        'unit',
        'sort',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (\floor12\ecommerce\models\entity\ParameterValue $model) {
                return
                    Html::button(\floor12\pages\assets\IconHelper::ARROW_UP, [
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => "ecommerceAdmin.orderChange('/shop/admin/value/order', {$model->id}, 4)",
                    ])
                    . " " .
                    Html::button(\floor12\pages\assets\IconHelper::ARROW_DOWN, [
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => "ecommerceAdmin.orderChange('/shop/admin/value/order', {$model->id}, 3)",
                    ]) . " " .
                    Html::a(\floor12\editmodal\IconHelper::PENCIL, NULL, [
                        'onclick' => EditModalHelper::showForm('/shop/admin/value/form', $model->id),
                        'class' => 'btn btn-default btn-sm'
                    ]) . " " .
                    Html::a(\floor12\editmodal\IconHelper::TRASH, NULL, [
                        'onclick' => EditModalHelper::deleteItem('/shop/admin/value/delete', $model->id),
                        'class' => 'btn btn-default btn-sm'
                    ]);
            },
        ]
    ],
]);

Pjax::end();