<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26/10/2018
 * Time: 20:06
 *
 * @var $this \yii\web\View
 * @var $row array
 */

use app\components\FontAwesome;
use yii\helpers\Html;

?>

<tr>

    <td class="cart-item-title">
        <?= $row['item']->title ?>
    </td>
    <td>
        <?php foreach ($row['item']->itemParamValues as $value) {
            echo Html::tag('div', "<span>{$value->param->title}:</span> <b>{$value->value} {$value->unit}</b>", ['class' => 'param']);
        } ?>

    </td>

    <td>
        <input name="Order[<?= $row['item']->id ?>][count]"
               value='<?= $row['quantity'] ?>'
               type="number"
               data-id="<?= $row['item']->id ?>"
               data-weight="<?= $row['item']->weight_delivery ?>"
               class="form-control cart-counter">
    </td>

    <td>
        <price><?= $row['price'] ?></price>
    </td>
    <td>
        <price><?= $row['sum'] ?></price>
    </td>
    <?php if ($editable): ?>
        <td class="text-right">
            <?= Html::tag('a', FontAwesome::icon('minus', 's'), [
                'class' => 'btn btn-default cart',
                'title' => 'Удалить из корзины',
                'data-id' => $row['item']->id
            ]); ?>
        </td>
    <?php endif; ?>
</tr>
