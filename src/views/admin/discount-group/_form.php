<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Parameter
 * @var $categories array
 *
 */

use floor12\ecommerce\models\enum\PriceType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create discount group' : 'Discount group updating'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <div class="row">

        <div class="col-md-3">
            <?= $form->field($model, 'item_quantity') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'discount_price_id')->dropDownList(
                PriceType::listData(), ['prompt' => Yii::t('app.f12.ecommerce', 'in percents')]
            ) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'discount_percent') ?>
        </div>

        <div class="col-md-3" style="padding-top: 30px;">
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>

    </div>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', $model->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
