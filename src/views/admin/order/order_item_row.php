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

?>


<tr>
    <td>
        <?= $model->productVariation->product->title ?>
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

