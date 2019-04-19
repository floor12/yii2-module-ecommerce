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
            <th><?= Yii::t('app.f12.ecommerce', 'Item title') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Quantity') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Price') ?></th>
            <th class="text-center"><?= Yii::t('app.f12.ecommerce', 'Total') ?></th>
            <th></th>
        </tr>
        <?php
        if ($model->rows)
            foreach ($model->rows as $row)
                echo $this->render('_index', ['row' => $row, 'editable' => true]);
        else
            echo Html::tag('div', Yii::t('app.f12.ecommerce', 'Your cart is empty.'), ['class' => 'text-center']);

        ?>
        </tbody>
    </table>
    <div class="cart-total">
        <?php if ($model->rows) echo Yii::t('app.f12.ecommerce', 'Total') . ': ' . Html::tag('span', $model->total) ?>
    </div>
</div>
<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.ecommerce', 'Close'), '', ['class' => 'btn btn-default modaledit-disable-silent']) ?>
    <?= ($model->rows) ? Html::a(Yii::t('app.f12.ecommerce', 'Checkout'), ['/shop/cart/checkout'], [
        'class' => 'btn btn-primary',
        'onclick' => 'offPageLeaving();',
    ]) : NULL ?>
</div>