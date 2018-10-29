<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 21/10/2018
 * Time: 18:56
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Item
 */

use floor12\ecommerce\components\AddToCartWidget;
use floor12\news\SwiperAsset;

SwiperAsset::register($this);

$this->registerJs('initItemsIndexSwiper()');

$this->params['breadcrumbs'][] = $model->categories[0]->title;
$this->params['breadcrumbs'][] = $model->title;

?>

<div class="f12-ec-item-view">
    <h1><?= $model->title ?></h1>

    <div class="row">

        <div class="col-md-7">

            <div class="f12-ec-item-view-description">
                <?= $model->description ?>
            </div>

            <h2><?= Yii::t('app.f12.ecommerce', 'Order options') ?></h2>

            <?= AddToCartWidget::widget(['item' => $model]) ?>

        </div>

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
    </div>

</div>