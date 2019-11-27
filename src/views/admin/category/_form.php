<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Category
 * @var $categories array
 * @var $parameters array
 *
 */


use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create category' : 'Category updating'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'parent_id')
                ->dropDownList($categories, ['prompt' => Yii::t('app.f12.ecommerce', 'none')]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'external_id') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'parameter_ids')->widget(Select2::class, [
                'data' => $parameters,
                'language' => 'ru',
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
