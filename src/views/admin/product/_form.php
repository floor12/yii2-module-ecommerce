<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Item
 * @var $categories array
 * @var $items array
 *
 */

use floor12\summernote\Summernote;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create product' : 'Update product'); ?></h2>
</div>

<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'seo_title') ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'category_ids')->widget(Select2::class, [
                'data' => $categories,
                'language' => 'ru',
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
            <?= $form->field($model, 'seo_description') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'external_id') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'article') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'weight_delivery', ['addon' => ['append' => ['content' => Yii::t('app.f12.ecommerce', 'kg')]]])
                ->textInput(['class' => 'text-right']) ?>
        </div>
        <div class="col-md-3" style="padding-top: 25px;">
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>

    <?php if (false && $model->itemParamValues): ?>
        <label>Атрибуты товара</label>
        <div class="f12-grey-block">
            <div class="row">
                <?php foreach ($model->itemParamValues as $itemParamValue)
                    echo Html::tag('div', "{$itemParamValue->param->title}: <b>{$itemParamValue->value} {$itemParamValue->unit}</b>", [
                        'class' => 'col-md-3'
                    ])
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'description')->widget(Summernote::class, []) ?>

    <?= $form->field($model, 'images')->widget(\floor12\files\components\FileInputWidget::class, []) ?>

</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
