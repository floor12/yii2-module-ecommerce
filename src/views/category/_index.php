<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:58
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Item
 *
 */

use floor12\news\SwiperAsset;

SwiperAsset::register($this);

$this->registerJs('initItemsIndexSwiper()');
?>

<div class="col-md-4">
    <div class="f12-ec-item">

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

        <a class="f12-ec-item-info" href="<?= $model->url ?>" data-pjax="0">
            <div class="f12-ec-item-title">
                <?= $model->title ?>
            </div>
            <div class="f12-ec-item-price">
                <price class='discount'><?= $model->price_discount ? Yii::$app->formatter->asCurrency($model->price_discount, Yii::$app->getModule('shop')->currency) : NULL ?></price>
                <price class="<?= $model->price_discount ? 'striked' : NULL ?>"><?= Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency) ?></price>
            </div>
        </a>
    </div>
</div>
