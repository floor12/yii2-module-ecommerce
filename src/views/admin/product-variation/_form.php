<?php
/**
 * @var $this yii\web\View
 * @var $model \floor12\ecommerce\models\forms\ProductVariationForm
 * @var $form yii\widgets\ActiveForm
 * @var $products array
 */

use floor12\ecommerce\models\enum\ParameterType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'id' => 'modal-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);
?>
    <div class='modal-header'>
        <h2><?= $model->isNewRecord ? '' : 'Редактирование' ?> объекта</h2>
    </div>

    <div class='modal-body'>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'price_0')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price_1')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price_2')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h3><?= Yii::t('app.f12.ecommerce', 'Product parameters') ?></h3>
                <?php
                if ($model->parameters) foreach ($model->parameters as $key => $param) {
                    if ($param['type_id'] == ParameterType::SLIDER)
                        echo $form->field($model, "parameterValues[{$key}]",
                            empty($param['unit']) ? [] : ['addon' => ['append' => ['content' => $param['unit']]]]
                        )
                            ->label($model->parameters[$key]->title);
                    else
                        echo $form->field($model, "parameterValues[{$key}]",
                            empty($param['unit']) ? [] : ['addon' => ['append' => ['content' => $param['unit']]]]
                        )
                            ->label($model->parameters[$key]->title)
                            ->widget(Select2::class, [
                                'data' => \floor12\ecommerce\models\entity\ParameterValue::find()
                                    ->select('value')
                                    ->indexBy('value')
                                    ->where(['parameter_id' => $key])
                                    ->orderBy('value')
                                    ->column(),
                                'pluginOptions' => [
                                    'placeholder' => '',
                                    'allowClear' => true,
                                    'tags' => true
                                ]
                            ]);
                } ?>
            </div>
            <div class="col-md-6">
                <h3><?= Yii::t('app.f12.ecommerce', 'Stocks balances') ?></h3>
                <table class="table table-striped">
                    <?php
                    if ($model->stocks)
                        foreach ($model->stocks as $key => $stock) { ?>
                            <tr>
                                <td>
                                    <?= $stock->title ?>
                                </td>
                                <td>
                                    <?= $form->field($model, "stockBalances[{$key}]", [
                                        'addon' => ['append' => ['content' => Yii::t('app.f12.ecommerce', 'pieces')]]])
                                        ->label(false); ?>
                                </td>
                            </tr>

                        <?php } ?>
                </table>
            </div>
        </div>


    </div>

    <div class='modal-footer'>
        <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>