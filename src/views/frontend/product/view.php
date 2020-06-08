<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 21/10/2018
 * Time: 18:56
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\Product
 * @var $productSelectorForm \floor12\ecommerce\models\forms\ProductSelectorForm
 */

use floor12\ecommerce\components\ParameterSelectorWidget;
use floor12\ecommerce\components\ProductStockBalanceWidget;
use frontend\components\ImageHelper;

$this->params['breadcrumbs']['/catalog.html'] = 'Каталог';
if (Yii::$app->request->referrer)
    $this->params['breadcrumbs'][Yii::$app->request->referrer] = $model->categories[0]->title;
$this->params['breadcrumbs'][] = $model->title;

?>

<div class="f12-ec-item-view">

    <?php if ($model->article): ?>
        <div class="f12-ec-item-article">
            Артикул:
            <span><?= $model->article ?></span>
        </div>
    <?php endif; ?>
    <h1><?= $model->title ?></h1>

    <div class="row">
        <div class="col-md-7 product-image-block">

            <div class="product-previews-block">
                <?php if ($model->images > 1) foreach ($model->images as $key => $image) { ?>
                    <a href="<?= $image->getPreviewWebPath(1300) ?>" target="_blank"
                       class="<?= !$key ? 'active' : null ?>"
                       data-sources="<?= "['{$image->getPreviewWebPath(650)}','{$image->getPreviewWebPath(1300)}','{$image->getPreviewWebPath(650, 0, 1)}','{$image->getPreviewWebPath(1300, 0, 1)}']" ?>">
                        <picture>
                            <source type="image/webp"
                                    srcset="<?= $image->getPreviewWebPath(80, 0, 1) ?> 1x, <?= $image->getPreviewWebPath
                                    (160, 0, 1) ?>
                                    2x">
                            <source type="image/jpeg"
                                    srcset="<?= $image->getPreviewWebPath(80) ?> 1x, <?= $image->getPreviewWebPath(160) ?>
                            2x">
                            <img src="<?= $image->getPreviewWebPath(80) ?>"
                                 alt="<?= $model->title ?>">
                        </picture>
                    </a>
                <?php } ?>
            </div>

            <?php if ($model->images): ?>
                <div id="product-main-image">
                    <picture>
                        <source type="image/webp"
                                srcset="<?= $model->images[0]->getPreviewWebPath(650, 0, 1) ?> 1x, <?= $model->images[0]->getPreviewWebPath
                                (1300, 0, 1) ?>
                                    2x">
                        <source type="image/jpeg"
                                srcset="<?= $model->images[0]->getPreviewWebPath(650) ?> 1x, <?= $model->images[0]->getPreviewWebPath(1300) ?>
                            2x">
                        <img src="<?= $model->images[0]->getPreviewWebPath(650) ?>"
                             alt="<?= $model->title ?>" class="zoomer">
                    </picture>
                </div>
            <?php endif; ?>

        </div>
        <div class="col-md-5">

            <div class="f12-ec-item-view-description">
                <?= $model->description ?>
            </div>

            <?= ParameterSelectorWidget::widget([
                'model' => $productSelectorForm,
                'product' => $model
            ]) ?>

        </div>
    </div>

</div>
