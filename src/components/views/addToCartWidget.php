<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26/10/2018
 * Time: 10:12
 *
 * @var $this \yii\web\View
 * @var $options \floor12\ecommerce\models\Item[]
 * @var $showProceedBtn boolean
 */

use app\components\FontAwesome;
use yii\helpers\Html;

?>


<table class="table table-striped item-options">
    <?php if ($options) foreach ($options as $option) { ?>
        <tr <?= !$option->available ? "class='f12-ec-item-na'" : NULL ?>>
            <td>
                <?= !$option->available ? Html::tag('div', Yii::t('app.f12.ecommerce', 'temporarily unavailable'), ['class' => 'f12-ec-item-na']) : NULL ?>

                <?= $option->article ? Html::tag('div', "<span>Артикул:</span> <b>{$option->article}</b>") : NULL ?>
                <?php foreach ($option->itemParamValues as $value) {
                    echo Html::tag('div', "<span>{$value->param->title}:</span> <b>{$value->value} {$value->unit}</b>");
                } ?>
            </td>
            <td>
                <div class="f12-ec-item-price">
                    <price class='discount'><?= $option->price_discount ? Yii::$app->formatter->asCurrency($option->price_discount, Yii::$app->getModule('shop')->currency) : NULL ?></price>
                    <price class="<?= $option->price_discount ? 'striked' : NULL ?>"><?= Yii::$app->formatter->asCurrency($option->price, Yii::$app->getModule('shop')->currency) ?></price>
                </div>
            </td>
            <td class="text-right">

                <?= $option->available ? Html::tag('a', FontAwesome::icon('cart-plus', 's'), [
                    'class' => isset($_COOKIE["cart-{$option->id}"]) ? 'btn btn-primary cart cart-active' : 'btn btn-default cart',
                    'title' => isset($_COOKIE["cart-{$option->id}"]) ? Yii::t('app.f12.ecommerce', 'Remove from cart') : Yii::t('app.f12.ecommerce', 'Add to cart'),
                    'data-id' => $option->id
                ]) : NULL; ?>

            </td>
        </tr>
    <?php } ?>
</table>

<div class="clearfix">
    <?=
    Html::a(FontAwesome::icon('check') . ' ' . Yii::t('app.f12.ecommerce', 'Proceed to checkout'),
        ['/shop/cart/checkout'],
        ['class' => $showProceedBtn ? 'btn btn-primary proceed-to-checkout' : 'btn btn-primary proceed-to-checkout proceed-to-checkout-hidden'])
    ?>
</div>
