<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $model \floor12\ecommerce\models\Order
 */

use floor12\phone\PhoneFormatter;
use yii\helpers\Html;
use yii\web\View;

?>
<p>
    <b><?= Yii::t('app.f12.ecommerce', 'New order') ?></b>
</p>

<ul>
    <li><?= Yii::t('app.f12.ecommerce', 'Fullname') ?>: <?= $model->fullname ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Email') ?>: <?= Yii::$app->formatter->asEmail($model->email) ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Phone') ?>: <?= PhoneFormatter::run($model->phone) ?></li>
    <li><?= Yii::t('app.f12.ecommerce', 'Total') ?>: <?= $model->total ?></li>
</ul>

<p>
    <?= Yii::t('app.f12.ecommerce', 'For order details click') ?>
    <?= Html::a(Yii::t('app.f12.ecommerce', 'this link'), ['/shop/admin/order']) ?>.
</p>