<?php
/**
 * @var $this \yii\web\View
 * @var $parameters \floor12\ecommerce\models\entity\Parameter[]
 * @var $parameterValuesList array
 * @var $product \floor12\ecommerce\models\entity\Product
 * @var $stockBalances array
 * @var $model \floor12\ecommerce\models\forms\ProductSelectorForm
 * @var $producatVariation \floor12\ecommerce\models\entity\ProductVariation
 * @var $priceCalculator \floor12\ecommerce\components\PriceCalculator
 *
 */

use kartik\form\ActiveForm;

$priceCalculator = Yii::$app->priceCalculator;
$priceCalculator->setProduct($product);

$form = ActiveForm::begin([
    'method' => 'GET',
    'action' => '/shop/frontend/product/parameter-selector-widget?id=' . $product->id,
    'id' => 'f12-eccomerce-cart-form',
    'options' => ['data-id' => $product->id, 'class' => 'cart-shadow']
]);

?>
<h2><?= Yii::t('app.f12.ecommerce', 'Order options') ?></h2>

<div class="row">
    <div class="col-sm-6 parameters-selector">
        <p class="info">
            Уточните, пожалуйста, параметры товара.
        </p>
        <?php
        foreach ($parameters as $parameter) { ?>

            <div class="form-group">
                <label class="control-label"
                       for="productselectorform-parametervalueids-<?= $parameter->id ?>"><?= $parameter->title ?></label>
                <div id="productselectorform-parametervalueids-<?= $parameter->id ?>" class="btn-group" data-toggle="buttons">
                    <?php foreach ($parameterValuesList[$parameter->id] as $parameterValue) { ?>
                        <label class="<?= $parameterValue['color_hex'] ? NULL : 'justify-centered' ?> btn btn-default <?=
                        $model->parameterValueIds[$parameter->id] == $parameterValue['id'] ? 'active' : NULL ?>">
                            <?php if ($parameterValue['color_hex'])
                                echo \yii\helpers\Html::tag('div', null, ['class' => 'color-box', 'style' => "background-color: {$parameterValue['color_hex']}"]) ?>
                            <input
                                <?= $model->parameterValueIds[$parameter->id] == $parameterValue['id'] ? 'checked="checked"' : NULL ?>
                                    type="radio" name="ProductSelectorForm[parameterValueIds][<?= $parameter->id ?>]"
                                    value="<?= $parameterValue['id'] ?>">
                            <?= $parameterValue['value'] ?>
                        </label>
                    <?php } ?>
                </div>
            </div>
        <? } ?>
    </div>
    <div class="col-sm-6">
        <button onclick="f12shop.addVariationToCart(event); return false;"
            <?= $producatVariation ? "class='f12-ec-product-view-cart' data-id={$producatVariation->id}" : "class='f12-ec-product-view-cart disabled'" ?>>
            <?= \floor12\editmodal\IconHelper::PLUS ?> <?= Yii::t('app.f12.ecommerce', 'add to cart') ?>
        </button>

        <div class="f12-ec-product-view-price">
            <price class='<?= $priceCalculator->hasDiscount() ? 'discount' : null ?>'>
                <?= Yii::$app->formatter->asCurrency($priceCalculator->getCurrentPrice(), Yii::$app->getModule('shop')->currency) ?>
            </price>
            <?php if ($priceCalculator->hasDiscount()): ?>
                <price class="striked">
                    <?= Yii::$app->formatter->asCurrency($priceCalculator->getOldPrice(), Yii::$app->getModule('shop')->currency) ?>
                </price>
                <div class="discount-in-percent">-<?= $priceCalculator->getDiscountInPercent() ?>%</div>
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

<p class="product-balance-info">
    Мы ценим ваше время, поэтому перед тем, как отправиться в магазин, позвоните нам и мы отложим для Вас желаемую модель и размер.
</p>

<?php \kartik\form\ActiveForm::end(); ?>




