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

    <td>
        <price><?= $model->price ?></price>
    </td>
    <td>
        <price><?= $model->sum ?></price>
    </td>
</tr>

