<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\forms\ItemParamsForm
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
    <h2><?= Yii::t('app.f12.ecommerce', 'Item parameters'); ?></h2>
</div>
<div class="modal-body">

    <?php
    echo $form->errorSummary($model);

    if ($model->params) foreach ($model->params as $key => $param) {

        if ($param['type_id'] == ParamType::STRING)
            echo $form->field($model, "params_values[{$key}]")
                ->label($model->params[$key]['label'])
                ->textInput(['value' => $model->params[$key]['value']]);
        else
            echo $form->field($model, "params_values[{$key}]")
                ->label($model->params[$key]['label'])
                ->widget(Select2::class, [
                    'value' => $model->params[$key]['value']
                ]);
    }
    ?>

</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
