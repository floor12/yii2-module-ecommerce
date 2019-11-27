<?php
/* @var $this yii\web\View */
/* @var $model floor12\ecommerce\models\entity\Stock */

/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'modal-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);
?>

    <div class='modal-header'>
        <h2><?= Yii::t('app.f12.ecommerce', !$model->isNewRecord ? 'Create stock' : 'Update stock') ?> </h2>
    </div>

    <div class='modal-body'>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'title_public')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'external_id')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4" style="padding-top: 30px;">
                <?= $form->field($model, 'status')->checkbox() ?>
            </div>
        </div>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    </div>

    <div class='modal-footer'>
        <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>