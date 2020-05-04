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
        <tr>
            <th></th>
            <th><?= Yii::t('app.f12.ecommerce', 'Product title') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Quantity') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Price') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Total') ?></th>
            <th></th>
        </tr>
        <?php
        if ($model->orderItems)
            foreach ($model->orderItems as $orderItem)
                echo $this->render('_index', ['model' => $orderItem, 'editable' => true]);
        else
            echo Html::tag('div', Yii::t('app.f12.ecommerce', 'Your cart is empty.'), ['class' => 'text-center']);

        ?>
        </tbody>
    </table>
    <div class="cart-total">
        <?php if (!empty($model->orderItems))
            echo Yii::t('app.f12.ecommerce', 'Total') . ': ' . Html::tag('span', $model->total) . Yii::$app->getModule('shop')->currency ?>
    </div>

    <?php
    if ($model->messages)
        foreach ($model->messages as $message)
            echo Html::tag('p', $message, ['class' => 'f12-discount-info']);
    ?>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Close'), '', ['class' => 'btn btn-default modaledit-disable-silent']) ?>
    <?= (!empty($model->orderItems)) ? Html::a(Yii::t('app.f12.ecommerce', 'Checkout'), ['/shop/frontend/cart/checkout'], [
        'class' => 'btn btn-primary',
        'onclick' => 'offPageLeaving();',
    ]) : NULL ?>
</div>
