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
    <h2>Товары в корзине</h2>
</div>
<div class="modal-body row">

    <div class="cart-content">
        <?php
        if ($model->orderItems)
            foreach ($model->orderItems as $orderItem)
                echo $this->render('_index', ['model' => $orderItem, 'editable' => true]);
        else
            echo Html::tag('div', Yii::t('app.f12.ecommerce', 'Your cart is empty.'), ['class' => 'text-center']);
        ?>
    </div>

    <div class="cart-total">
        <?php if (!empty($model->orderItems))
            echo 'Всего в корзине: ' . Yii::$app->formatter->asCurrency($model->total, Yii::$app->getModule('shop')->currency) ?>
    </div>

    <?php
    if ($model->messages)
        foreach ($model->messages as $message)
            echo Html::tag('p', $message, ['class' => 'f12-discount-info']);
    ?>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Close'), '', ['class' => 'btn btn-default pull-left modaledit-disable-silent']) ?>
    <?= (!empty($model->orderItems)) ? Html::a(Yii::t('app.f12.ecommerce', 'Checkout'), ['/shop/frontend/cart/checkout'], [
        'class' => 'btn btn-primary',
        'onclick' => 'offPageLeaving();',
    ]) : NULL ?>
</div>
