<?php


use floor12\ecommerce\components\TabWidget;
use floor12\ecommerce\models\enum\Status;
use floor12\editmodal\EditModalColumn;
use floor12\editmodal\EditModalHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('app.f12.ecommerce', 'Stocks');
$this->params['breadcrumbs'][] = Yii::t('app.f12.ecommerce', 'Store administration');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(\floor12\editmodal\IconHelper::PLUS . " " . Yii::t('app.f12.ecommerce', 'Create stock'), null, [
    'onclick' => EditModalHelper::showForm(['form'], 0),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

$form = ActiveForm::begin([
    'method' => 'GET',
    'options' => ['class' => 'autosubmit', 'data-container' => '#items'],
    'enableClientValidation' => false,
]); ?>
    <div class="filter-block">
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'filter')
                    ->label(false)
                    ->textInput([
                        'placeholder' => Yii::t('app.f12.ecommerce', 'find stock'),
                        'autofocus' => true
                    ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')
                    ->label(false)
                    ->dropDownList([], [
                        'prompt' => Yii::t('app.f12.ecommerce', 'any status')
                    ]) ?>
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
    'rowOptions' => function (\floor12\ecommerce\models\entity\Stock $model) {
        if ($model->status == Status::DISABLED)
            return ['class' => 'disabled'];
    },
    'columns' => [
        'id',
        'title',
        'title_public',
        'url:url',
        [
            'header' => Yii::t('app.f12.ecommerce', 'Products count'),
            'content' => function (\floor12\ecommerce\models\entity\Stock $model) {
                $model->getStockBalances()->total();
            }

        ],
        [
            'class' => EditModalColumn::class,
        ],
    ],
]);

Pjax::end();