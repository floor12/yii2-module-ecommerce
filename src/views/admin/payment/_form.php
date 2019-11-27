<?php
    /* @var $this yii\web\View */
    /* @var $model floor12\ecommerce\models\entity\Payment */
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
        <h2><?= $model->isNewRecord ? 'Создание' : 'Редактирование' ?>  объекта</h2>
    </div>

    <div class='modal-body'>

            <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput() ?>

    <?= $form->field($model, 'payed')->textInput() ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'external_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'form_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'external_status')->textInput() ?>

    </div>

    <div class='modal-footer'>
        <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?> 
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?> 
    </div>

<?php ActiveForm::end(); ?>