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
use floor12\ecommerce\models\Category;
use floor12\ecommerce\models\enum\Status;
use floor12\editmodal\EditModalHelper;
use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\helpers\Html;
use yii\widgets\Pjax;

EcommerceAsset::register($this);

$columns = [
    [
        'attribute' => 'title',
        'content' => function (Category $model) {
            if (!$model->parent_id)
                return Html::tag('b', $model->title);
            return $model->title;
        }
    ],
    'id',
    'items_total',
    'params_total',
    'children_total',
    ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
        'content' => function (Category $model) {
            return
                Html::a(FontAwesome::icon('pencil'), NULL, ['onclick' => EditModalHelper::showForm('shop/admin/category-form', $model->id), 'class' => 'btn btn-default btn-sm']) . " " .
                Html::a(FontAwesome::icon('trash'), NULL, ['onclick' => EditModalHelper::deleteItem('shop/admin/category-delete', $model->id), 'class' => 'btn btn-default btn-sm']);
        },
    ]
];

$this->title = Yii::t('app.f12.ecommerce', 'Categories');

echo Html::tag('h1', Yii::t('app.f12.ecommerce', 'Shop'));

echo TabWidget::widget();

echo Html::a(FontAwesome::icon('plus') . " " . Yii::t('app.f12.ecommerce', 'Create category'), null, [
    'onclick' => EditModalHelper::showForm('shop/admin/category-form', 0),
    'class' => 'btn btn-sm btn-primary btn-ecommerce-add'
]);

echo Html::tag('br');

$form = ActiveForm::begin([
    'method' => 'GET',
    'options' => ['class' => 'autosubmit', 'data-container' => '#items'],
]);
?>

    <div class="item-filter">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'filter')
                    ->label(false)
                    ->textInput(['placeholder' => Yii::t('app.f12.ecommerce', 'categories search')]) ?>
            </div>
        </div>
    </div>

<?php
ActiveForm::end();

Pjax::begin(['id' => 'items']);

if ($model->filter)
    echo \yii\grid\GridView::widget([
        'dataProvider' => $model->dataProvider(),
        'tableOptions' => ['class' => 'table table-striped'],
        'rowOptions' => function (Category $model) {
            if ($model->status == Status::DISABLED)
                return ['class' => 'disabled'];
        },
        'layout' => "{items}\n{pager}\n{summary}",
        'columns' => $columns
    ]);
else
    echo \leandrogehlen\treegrid\TreeGrid::widget([
        'dataProvider' => $model->dataProvider(),
        //'filterModel' => $searchModel,
        'options' => ['class' => 'table'],
        'rowOptions' => function (Category $model) {
            if ($model->status == Status::DISABLED)
                return ['class' => 'disabled'];
        },
        'keyColumnName' => 'id',
        'parentColumnName' => 'parent_id',
        'parentRootValue' => null, //first parentId value
        'pluginOptions' => [
            'initialState' => 'collapsed',
            'saveState' => true,
        ],
        'columns' => $columns

    ]);

Pjax::end();

