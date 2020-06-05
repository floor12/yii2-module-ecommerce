<?php
/* @var $this yii\web\View */
/* @var $model floor12\ecommerce\models\entity\ParameterValue */

/* @var $form yii\widgets\ActiveForm */

use kartik\color\ColorInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'modal-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);
?>

<div class='modal-header'>
    <h2><?= $model->isNewRecord ? 'Создание' : 'Редактирование' ?> объекта</h2>
</div>

<div class='modal-body'>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'color_hex')->widget(ColorInput::class,[
                'useNative' => true,
            ]); ?>
        </div>
    </div>

</div>

<div class='modal-footer'>
    <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
