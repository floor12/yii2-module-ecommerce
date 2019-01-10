<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/11/2018
 * Time: 20:59
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\OrderItem
 */

use yii\helpers\Html;

?>


<tr>
    <td>
        <?= $model->item->title ?>
    </td>
    <td>
        <?php foreach ($model->item->itemParamValues as $value) {
            echo Html::tag('div', "<span>{$value->param->title}:</span> <b>{$value->value} {$value->unit}</b>", ['class' => 'param']);
        } ?>

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

