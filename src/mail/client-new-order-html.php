<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $model \floor12\ecommerce\models\entity\Order
 * @var $paymentLink string
 */

use yii\helpers\Html;
use yii\web\View;

?>
<p>
    <b><?= Yii::t('app.f12.ecommerce', 'Hello') ?>, <?= $model->fullname ?>!</b>
</p>

<p>
    <?= Yii::t('app.f12.ecommerce', 'Thanks for purchase') ?>.
    <?= Yii::t('app.f12.ecommerce', 'Your order number is') ?>: <b><?= $model->id ?></b>
</p>

<?php if ($paymentLink)
    echo Html::a(Yii::t('app.f12.ecommerce', 'Link to the payment page.'), $paymentLink) ?>

<p>
    <?= Yii::t('app.f12.ecommerce', 'Our managers will contact you in the nearest future to confirm your order.'); ?>
</p>
