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
use yii\helpers\Html;


?>

<div class="col-md-4">
    <?= FavWidget::widget(['id' => $model->id]) ?>
    <a class="f12-ec-product" href="<?= \yii\helpers\Url::toRoute(['/shop/frontend/product/view', 'id' => $model->id]) ?>">
        <?php if ($model->images) { ?>
            <div class="f12-ec-product-image-wrapper">
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

        <div class="f12-ec-product-info">
            <div class="f12-ec-product-title">
                <?= $model->title ?>
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
                <price class='<?= $model->getPriceOld() ? 'discount' : null ?>'><?= Yii::$app->formatter->asCurrency($model->price,
                        Yii::$app->getModule('shop')->currency)
                    ?></price>
                <?php if ($model->priceOld): ?>
                    <price class="striked">
                        <?= Yii::$app->formatter->asCurrency($model->priceOld, Yii::$app->getModule('shop')->currency) ?>
                    </price>
                <?php endif; ?>
            </div>
        </div>
    </a>
</div>
