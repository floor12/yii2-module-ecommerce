<?php
/**
 * Created by PhpStorm.
 * User: evgenygoryaev
 * Date: 14/08/2017
 * Time: 15:47
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\forms\CartForm
 */

use yii\helpers\Html;

?>

<div class="modal-header">
    <h2><?= Yii::t('app.f12.ecommerce', 'Cart') ?></h2>
</div>
<div class="modal-body row">
    <table class="table table-striped table-cart">
        <tbody>
        <?php if ($model->rows) foreach ($model->rows as $row) echo $this->render('_index', ['row' => $row, 'editable' => true]) ?>
        </tbody>
    </table>
    <div class="cart-total">
        <?= Yii::t('app.f12.ecommerce', 'Total') ?>: <span><?= $model->total ?></span>
    </div>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Close'), '', ['class' => 'btn btn-default modaledit-disable-silent']) ?>
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Checkout'), ['/shop/cart/checkout'], [
        'class' => 'btn btn-primary',
        'onclick' => 'offPageLeaving();',
    ]) ?>
</div>