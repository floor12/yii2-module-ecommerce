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

<article class="<?= !empty($row['message']) ? 'f12-cart-discounted' : NULL ?>">
    <div class="cart-image">
        <?php if (!empty($model->productVariation->product->images))
            echo Html::img($model->productVariation->product->images[0]->getPreviewWebPath(300)); ?>
    </div>
    <div class="cart-product-data">
        <div class="cart-item-title">
            <?= Html::a($model->productVariation->product->title, [
                '/shop/frontend/product/view',
                'id' => $model->productVariation->product->id
            ], [
                'target' => '_blank'
            ]) ?>

            <?= !$model->productVariation->getStockBalances()->sum('balance') ? Html::tag('div', 'нет на складе', ['class' =>
                'f12-ec-item-na']) : NULL ?>

            <div class="cart-item-title-params">
                <?= $model->productVariation->product->article ? "Артикул: {$model->productVariation->product->article}<br>" : NULL ?>
                <?= implode(', ', $model->productVariation->parameterValues) ?>
            </div>
        </div>
        <div class="cart-item-count">
            Кол-во: <input name="Order[<?= $model->product_variation_id ?>][count]"
                           value='<?= $model->quantity ?>'
                           type="number"
                           disabled="<?= !$editable ? 'disabled' : NULL ?>"
                           data-id="<?= $model->product_variation_id ?>"
                           data-weight="<?= $model->productVariation->product->weight_delivery ?>"
                           class="cart-counter">
        </div>

        <div class="cart-item-price">
            <price>
                <?= Yii::$app->formatter->asCurrency($model->price, Yii::$app->getModule('shop')->currency) ?>
            </price>
            <?php if ($model->full_price != $model->price): ?>
                <price class="striked">
                    <?= Yii::$app->formatter->asCurrency($model->full_price, Yii::$app->getModule('shop')->currency) ?>
                </price>
                <div class="discount-in-percent">-<?= $model->discount_percent ?>%</div>
            <?php endif; ?>
        </div>
        <?php if ($editable): ?>
            <div class="pull-right">
                <?= Html::tag('a', \floor12\editmodal\IconHelper::TRASH, [
                    'class' => 'btn btn-default cart-delete',
                    'title' => 'Удалить из корзины',
                    'data-id' => $model->productVariation->id
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</article>
