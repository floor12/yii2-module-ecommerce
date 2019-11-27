<?php
/**
 * @var $this \yii\web\View
 * @var $parameters \floor12\ecommerce\models\entity\Parameter[]
 * @var $parameterValuesList array
 * @var $product \floor12\ecommerce\models\entity\Product
 * @var $stockBalances array
 * @var $model \floor12\ecommerce\models\forms\ProductSelectorForm
 * @var $producatVariation \floor12\ecommerce\models\entity\ProductVariation
 *
 */

use kartik\form\ActiveForm;

$form = ActiveForm::begin([
    'method' => 'GET',
//    'action' => '/shop/frontend/product/parameter-selector-widget?id=' . $product->id,
    'id' => 'f12-eccomerce-cart-form',
    'options' => ['data-id' => $product->id, 'class' => 'cart-shadow']
]);
?>
    <h2><?= Yii::t('app.f12.ecommerce', 'Order options') ?></h2>

    <div class="row">
        <div class="col-sm-6">
            <?php
            foreach ($parameters as $parameter) {

                echo $form->field($model, "parameterValueIds[{$parameter->id}]")
                    ->label($parameter->title)
                    ->widget(\yii\bootstrap\ToggleButtonGroup::class, [
                        'items' => $parameterValuesList[$parameter->id],
                        'type' => 'radio',
                        'options' => ['class' => 'btn-group'],
                        'labelOptions' => ['class' => 'btn btn-default']
                    ]);
            } ?>
        </div>
        <div class="col-sm-6">
            <button onclick="f12shop.addVariationToCart(event); return false;"
                    class="f12-ec-product-view-cart" <?= $producatVariation ? "data-id={$producatVariation->id}" : "disabled='disabled'"
            ?>>
                <?= \floor12\editmodal\IconHelper::PLUS ?> <?= Yii::t('app.f12.ecommerce', 'add to cart') ?>
            </button>

            <div class="f12-ec-product-view-price">
                <price class='<?= $product->getPriceOld() ? 'discount' : null ?>'>
                    <?= Yii::$app->formatter->asCurrency($product->price, Yii::$app->getModule('shop')->currency) ?>
                </price>
                <?php if ($product->priceOld): ?>>
                    <price class="striked">
                        <?= Yii::$app->formatter->asCurrency($product->priceOld, Yii::$app->getModule('shop')->currency) ?>
                    </price>
                <?php endif; ?>
            </div>

        </div>
    </div>


    <h2>Наличие в магазинах</h2>
<?php if ($stockBalances): ?>
    <ul class="product-stock-balances">
        <?php
        foreach ($stockBalances as $stock) {
            if (empty($stock['url']))
                echo "<li>{$stock['title_public']}</li>";
            else
                echo "<li><a href='{$stock['url']}' target='_blank'>{$stock['title_public']}</a><div class='small'>{$stock['address']}</div></li>";
        } ?>
    </ul>
<?php else: ?>
    К сожалению, товара с выбранными параметрами сейчас нет в наличии.
<?php endif; ?>
<?php \kartik\form\ActiveForm::end();


