<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\EcItemParam
 * @var $categories array
 *
 */

use floor12\ecommerce\models\enum\ParamType;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create parameter' : 'Parameter updating'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'type_id')->dropDownList(ParamType::listData()) ?>

            <?= $form->field($model, 'unit') ?>
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

            <?= $form->field($model, 'external_id') ?>

            <?= $form->field($model, 'hide')->checkbox() ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create' : 'Update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
