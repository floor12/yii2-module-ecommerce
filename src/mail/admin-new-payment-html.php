<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $model ->order \floor12\ecommerce\models\entity\Payment
 */

use floor12\phone\PhoneFormatter;
use yii\helpers\Html;
use yii\web\View;

?>
<p>
    <b><?= Yii::t('app.f12.ecommerce', 'New success payment') ?></b>
</p>

<ul>
    <li><?= Yii::t('app.f12.ecommerce', 'Fullname') ?>: <?= $model->order->fullname ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Email') ?>: <?= Yii::$app->formatter->asEmail($model->order->email) ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Phone') ?>: <?= PhoneFormatter::run($model->order->phone) ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Total') ?>: <?= $model->order->total ?></li>
</ul>

<p>
    <?= Yii::t('app.f12.ecommerce', 'For order details click') ?>
    <?= Html::a(Yii::t('app.f12.ecommerce', 'this link'), Yii::$app->urlManager->createAbsoluteUrl(['/shop/admin/order'])) ?>.
</p>
