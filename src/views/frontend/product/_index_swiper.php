<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:58
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Product
 *
 */

use floor12\ecommerce\components\FavWidget;
use floor12\news\SwiperAsset;
use yii\helpers\Html;

SwiperAsset::register($this);

$this->registerJs('initItemsIndexSwiper()');
?>

<div class="col-md-4">
    <div class="f12-ec-item">
        <?= FavWidget::widget(['id' => $model->id]) ?>
        <div class="swiper-container swiper-list">
            <div class="swiper-wrapper">
                <?php if ($model->images) { ?>
                    <div class="swiper-slide">
                        <picture>
                            <source type="image/webp"
                                    srcset="<?= $model->images[0]->getPreviewWebPath(350, 0, 1) ?> 1x, <?= $model->images[0]->getPreviewWebPath(700, 0, 1) ?>
                                    2x">
                            <source type="image/jpeg"
                                    srcset="<?= $model->images[0]->getPreviewWebPath(350) ?> 1x, <?= $model->images[0]->getPreviewWebPath(700) ?> 2x">
                            <img src="<?= $model->images[0]->getPreviewWebPath(350) ?>"
                                 alt="<?= $model->title ?>, изображение №<?= ++$key ?>">
                        </picture>
                    </div>
                <?php } ?>
            </div>
            <!--            <div class="swiper-pagination"></div>-->
            <!--            <div class="swiper-button-next"></div>-->
            <!--            <div class="swiper-button-prev"></div>-->
        </div>

        <a class="f12-ec-item-info" href="<?= $model->url ?>" data-pjax="0">
            <div class="f12-ec-item-title">
                <?= $model->title ?>
                <div class="small">
                    <?php if ($model->getParameterValues(1751)) foreach ($model->getParameterValues(1751) as $row) {
                        echo Html::tag('span', $row['value'], ['class' => empty($row['total']) ? 'size empty' : 'size']);
                    } ?>
                </div>
            </div>
            <div class="f12-ec-item-price">
                <price class='<?= $model->getPriceOld() ? 'discount' : null ?>'><?= Yii::$app->formatter->asCurrency($model->price,
                        Yii::$app->getModule('shop')->currency)
                    ?></price>
                <?php if ($model->priceOld): ?>>
                    <price class="striked">
                        <?= Yii::$app->formatter->asCurrency($model->priceOld, Yii::$app->getModule('shop')->currency) ?>
                    </price>
                <?php endif; ?>
            </div>
        </a>
    </div>
</div>
