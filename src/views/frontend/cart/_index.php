<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26/10/2018
 * Time: 20:06
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\entity\OrderItem
 */

use yii\helpers\Html;

?>

<tr class="<?= !empty($row['message']) ? 'f12-cart-discounted' : NULL ?>">
    <td class="cart-image">
        <?php if (!empty($model->productVariation->product->images))
            echo Html::img($model->productVariation->product->images[0]->getPreviewWebPath(150)); ?>
    </td>
    <td class="cart-item-title">
        <?= Html::a($model->productVariation->product->title . "<div class='article'>{$model->productVariation->product->article}</div>", [
            '/shop/frontend/product/view',
            'id' => $model->productVariation->product->id
        ], [
            'target' => '_blank'
        ]) ?>

        <?= !$model->productVariation->getStockBalances()->sum('balance') ? Html::tag('div', 'нет на складе', ['class' =>
            'f12-ec-item-na']) : NULL ?>


        <div class="cart-item-title-params">
            <?= implode(', ', $model->productVariation->parameterValues) ?>
        </div>
    </td>
    <td class="text-center">
        <input name="Order[<?= $model->product_variation_id ?>][count]"
               value='<?= $model->quantity ?>'
               type="number"
               data-id="<?= $model->product_variation_id ?>"
               data-weight="<?= $model->productVariation->product->weight_delivery ?>"
               class="form-control cart-counter">
    </td>

    <td class="text-center">
        <price>
            <?= Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency) ?>
        </price>
    </td>
    <td class="text-center">
        <price>
            <?= Yii::$app->formatter->asCurrency($model->sum, Yii::$app->getModule('shop')->currency) ?>
        </price>
    </td>
    <?php if ($editable): ?>
        <td class="text-right">
            <?= Html::tag('a', \floor12\editmodal\IconHelper::TRASH, [
                'class' => 'btn btn-default cart-delete',
                'title' => 'Удалить из корзины',
                'data-id' => $model->productVariation->id
            ]); ?>
        </td>
    <?php endif; ?>
</tr>
