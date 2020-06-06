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
    <div class="pull-right">
        <?= \floor12\editmodal\EditModalHelper::btnClose() ?>
    </div>
    <h2>Товары в корзине</h2>
</div>

<div class="modal-body row">
    <?= $this->render('index', ['model' => $model]) ?>
</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Close'), '', ['class' => 'btn btn-default pull-left modaledit-disable-silent']) ?>
    <?= (!empty($model->orderItems)) ? Html::a(Yii::t('app.f12.ecommerce', 'Checkout'), ['/shop/frontend/cart/checkout'], [
        'class' => 'btn btn-primary',
        'onclick' => 'offPageLeaving();',
    ]) : NULL ?>
</div>
