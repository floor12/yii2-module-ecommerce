<?php
/**
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\ProductVariation
 */

use floor12\editmodal\EditModalHelper;

?>

<tr>
    <td>
        id:<b><?= $model->id ?></b> | <?= implode(', ', $model->parameterValues) ?>
    </td>
    <td style="text-align: center; width: 70px;">
        <?= $model->getStockBalances()->sum('balance') ?> <?= Yii::t('app.f12.ecommerce', 'pieces') ?>
    </td>
    <td style="text-align: right; width: 70px; ">
        <?= EditModalHelper::editBtn('/shop/admin/product-variation/form', $model->id, 'btn btn-default btn-xs') ?>
        <?= EditModalHelper::deleteBtn('/shop/admin/product-variation/delete', $model->id, 'btn btn-default btn-xs') ?>
    </td>
</tr>
