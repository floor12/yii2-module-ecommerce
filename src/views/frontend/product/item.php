<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 21/10/2018
 * Time: 18:56
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Product
 */

use floor12\ecommerce\components\AddToCartWidget;
use floor12\ecommerce\components\AddToCartWidgetAjax;

//SwiperAsset::register($this);

//$this->registerJs('initItemsIndexSwiper()');

$this->title = $model->title;
$this->params['breadcrumbs'][] = $model->categories[0]->title;
$this->params['breadcrumbs'][] = $model->title;

?>

<div class="f12-ec-item-view">
    <h1><?= $model->title ?></h1>

    <div class="row">
        <div class="col-md-5">
            <div class="swiper-container swiper-full">
                <div class="swiper-wrapper">
                    <?php foreach ($model->images as $image) { ?>
                        <div class="swiper-slide"
                             style="background-image: url(<?= $image->getPreviewWebPath(700) ?>); background-size: cover;"></div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </div>

        <div class="col-md-7">

            <div class="f12-ec-item-view-description">
                <?= $model->description ?>
            </div>
            <br>
            <br>
            <div class="small"><?= Yii::t('app.f12.ecommerce', 'Price'); ?>:</div>
            <?php if (sizeof($model->prices) > 1): ?>
                <price>
                    <?= Yii::$app->formatter->asCurrency($model->prices[0], Yii::$app->getModule('shop')->currency) ?>
                    -
                    <?= Yii::$app->formatter->asCurrency($model->prices[1], Yii::$app->getModule('shop')->currency) ?>
                </price>
            <?php else: ?>
                <price class='discount'><?= $model->price_discount ? Yii::$app->formatter->asCurrency($model->price_discount, Yii::$app->getModule('shop')->currency) : NULL ?></price>
                <price class="<?= $model->price_discount ? 'striked' : NULL ?>"><?= Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency) ?></price>
            <?php endif; ?>

            <h2><?= Yii::t('app.f12.ecommerce', 'Order options') ?></h2>

            <?=
            Yii::$app->getModule('shop')->useAjaxAddToCartWidget ?
                AddToCartWidgetAjax::widget(['item' => $model]) :
                AddToCartWidget::widget(['item' => $model])
            ?>


        </div>

    </div>

</div>