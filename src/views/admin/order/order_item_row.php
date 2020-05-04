<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/11/2018
 * Time: 20:59
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\OrderItem
 */

use yii\helpers\Html;

?>


<tr>
    <td class="order-product-image">
        <?php if (!empty($model->productVariation->product->images))
            echo Html::img($model->productVariation->product->images[0]->getPreviewWebPath(150)); ?>
    </td>
    <td>
        <b><?= $model->productVariation->product->article ?></b>
        <div class="small"><?= $model->productVariation->product->title ?><br></div>
    </td>
    <td>
        <?= implode(', ', $model->productVariation->parameterValues) ?>
    </td>

    <td>
        <?= $model->quantity ?>
    </td>

    <td class="text-center">
        <price>
            <?= Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency) ?>
        </price>
        <?php if ($model->full_price != $model->price): ?>
            <price class="striked">
                <?= Yii::$app->formatter->asCurrency($model->full_price, Yii::$app->getModule('shop')->currency) ?>
            </price>
            <div class="discount-in-percent">-<?= $model->discount_percent ?>%</div>
        <?php endif; ?>
    </td>
    <td>
        <price><?= $model->sum ?></price>
    </td>
</tr>

