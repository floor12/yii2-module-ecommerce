<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 11:13
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Order
 * @var $categories array
 *
 */

use floor12\ecommerce\models\enum\DeliveryType;
use floor12\ecommerce\models\enum\OrderStatus;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', 'Order updating'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <table class="table table-striped table-bordered table-cart">
        <tbody>
        <tr>
            <th><?= Yii::t('app.f12.ecommerce', 'Item title') ?></th>
            <th><?= Yii::t('app.f12.ecommerce', 'Parameters') ?></th>
            <th><?= Yii::t('app.f12.ecommerce', 'Quantity') ?></th>
            <th><?= Yii::t('app.f12.ecommerce', 'Price') ?></th>
            <th><?= Yii::t('app.f12.ecommerce', 'Sum') ?></th>
        </tr>
        <?php if ($model->orderItems) foreach ($model->orderItems as $item) echo $this->render('order_item_row', ['model' => $item]) ?>
        </tbody>
    </table>
    <div class="cart-total">
        <div> <?= Yii::t('app.f12.ecommerce', 'Total items cost') ?>:
            <span><?= $model->items_cost ?> <?= Yii::$app->getModule('shop')->currencyLabel ?></span></div>
        <div> <?= Yii::t('app.f12.ecommerce', 'Delivery cost') ?>:
            <span><?= $model->delivery_cost ?> <?= Yii::$app->getModule('shop')->currencyLabel ?></span></div>
        <div><?= Yii::t('app.f12.ecommerce', 'Total') ?>: <span><?= $model->total ?>
                <?= Yii::$app->getModule('shop')->currencyLabel ?></span></div>
    </div>

    <br>
    <br>
    <br>
    <br>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($model, 'fullname') ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                'mask' => '+9 (999) 999-99-99'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($model, 'delivery_type_id')->dropDownList(DeliveryType::listData()) ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'status')->dropDownList(OrderStatus::listData()) ?>
        </div>
    </div>


    <?= $form->field($model, 'address')
        ->label(Yii::t('app.f12.ecommerce', 'Additional comment'))
        ->textarea(['rows' => 4])
    ?>


    <?= $form->field($model, 'comment')
        ->label(Yii::t('app.f12.ecommerce', 'Additional comment'))
        ->textarea(['rows' => 4])
    ?>


</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.ecommerce', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
