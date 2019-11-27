<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26/10/2018
 * Time: 10:12
 *
 * @var $this \yii\web\View
 * @var $params array
 * @var int $item_id
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJs("itemOptionsurl = '" . Url::toRoute(['shop/frontend/cart/options']) . "'");

?>
<form id="add-to-cart-ajax" class="cart-shadow">
    <?= Html::hiddenInput('item_id', $item_id) ?>
    <div class="row">
        <?php if ($params) foreach ($params as $paramId => $paramData) { ?>
            <div class="col-xs-6 col-sm-4">
                <div class="param-label"><?= $paramData['title'] ?>:</div>
                <?= Html::dropDownList("params[{$paramId}]", null, $paramData['values'], [
                    'prompt' => Yii::t('app.f12.ecommerce', 'not selected'),
                    'class' => 'form-control'
                ]) ?>
            </div>
        <?php } ?>
        <div class="col-xs-6 col-sm-4">
            <div class="price-actual" id="price-actual">

            </div>
            <button id='addToCartAjaxBtn' class="btn btn-primary btn-sm btn-block"
                    disabled="disabled"><?= Yii::t('app.f12.ecommerce', 'Add to cart') ?></button>
        </div>
    </div>
</form>

