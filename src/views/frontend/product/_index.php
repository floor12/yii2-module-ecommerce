<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:58
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Product
 * @var $priceCalculator \floor12\ecommerce\components\PriceCalculator
 */

use floor12\ecommerce\components\FavWidget;
use yii\helpers\Html;

$priceCalculator = Yii::$app->priceCalculator;
$priceCalculator->setProduct($model);
?>

<div class="col-md-4">
    <?= FavWidget::widget(['id' => $model->id]) ?>
    <?php if ($priceCalculator->hasDiscount()): ?>
        <div class="discount-in-percent">-<?= $priceCalculator->getDiscountInPercent() ?>%</div>
    <?php endif; ?>
    <a class="f12-ec-product" href="<?= \yii\helpers\Url::toRoute(['/shop/frontend/product/view', 'id' => $model->id]) ?>">
        <?php if ($model->images && is_file($model->images[0]->getRootPath())) { ?>
            <div class="f12-ec-product-image-wrapper">
                <picture>
                    <source type="image/webp"
                            srcset="<?= $model->images[0]->getPreviewWebPath(350, 0, 1) ?> 1x, <?= $model->images[0]->getPreviewWebPath(700, 0, 1) ?>
                                    2x">
                    <source type="image/jpeg"
                            srcset="<?= $model->images[0]->getPreviewWebPath(350) ?> 1x, <?= $model->images[0]->getPreviewWebPath(700) ?> 2x">
                    <img src="<?= $model->images[0]->getPreviewWebPath(350) ?>"
                         alt="Изображение товара <?= $model->title ?>">
                </picture>
            </div>
        <?php } ?>

        <div class="f12-ec-product-info">
            <div class="f12-ec-product-title">
                <?= $model->title ?> <span class="f12-ec-product-list-article"><?= $model->article ?></span>
                <div class="small">
                    <?php
                    $parameterId = Yii::$app->getModule('shop')->mainParameterId;
                    if ($parameterId && $model->getParameterValues($parameterId)) foreach
                    ($model->getParameterValues($parameterId) as
                     $row) {
                        echo Html::tag('span', $row['value'], ['class' => empty($row['total']) ? 'size empty' : 'size']);
                    } ?>
                </div>
            </div>
            <div class="f12-ec-product-price">

                <div class='price <?= $priceCalculator->hasDiscount() ? 'discount' : null ?>'>
                    <?= Yii::$app->formatter->asCurrency($priceCalculator->getCurrentPrice(), Yii::$app->getModule('shop')->currency) ?>
                </div>
                <?php if ($priceCalculator->hasDiscount()): ?>
                    <div class="price striked">
                        <?= Yii::$app->formatter->asCurrency($priceCalculator->getOldPrice(), Yii::$app->getModule('shop')->currency) ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </a>
</div>
