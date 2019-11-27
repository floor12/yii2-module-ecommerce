<?php


use floor12\editmodal\EditModalHelper;
use floor12\editmodal\IconHelper;
use floor12\editmodal\EditModalColumn;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


$this->title = 'Список';
$this->params['breadcrumbs'][] = $this->title;

echo Html::button(IconHelper::PLUS . ' Добавить объект', [
'onclick' => EditModalHelper::showForm(['form'], 0),
'class' => 'btn btn-primary btn-sm pull-right'
])

?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'GET',
    'options' => ['class' => 'autosubmit', 'data-container' => '#items'],
    'enableClientValidation' => false,
]); ?>
    <div class="filter-block">
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model,'filter')->label(false)->textInput(['placeholder'=>'Поиск','autofocus' => true])?>            </div>
            <div class="col-md-3">
                <?= $form->field($model,'status')->label(false)->dropDownList([],['prompt'=>'Все статусы'])?>            </div>
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
            'product_id',
            'price_0',
            'price_1',
            'price_2',
        [
        'class' => EditModalColumn::class,
        'contentOptions' => ['style' => 'min-width:100px;', 'class' => 'text-right'],
        ],
    ],
]);

Pjax::end();