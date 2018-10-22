<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 21/10/2018
 * Time: 18:56
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\EcItem
 */

use floor12\news\SwiperAsset;

SwiperAsset::register($this);

$this->registerJs('initItemsIndexSwiper()');

?>


<h1><?= $model->title ?></h1>

<div class="row">
    <div class="col-md-8">

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
</div>