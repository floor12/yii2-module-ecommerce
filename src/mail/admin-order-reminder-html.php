<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $models \floor12\ecommerce\models\entity\Order[]
 */

use floor12\ecommerce\models\enum\OrderStatus;
use floor12\phone\PhoneFormatter;
use yii\helpers\Html;
use yii\web\View;

?>
<p>
    <b>На текущий момент (<?= Yii::$app->formatter->asDate(time()) ?>) имеются следующие незавершенные заказы:</b>
</p>

<?php foreach ($models as $model): ?>
    <p>
        <b>Заказ №<?= $model->id ?></b>. Статус: <?= OrderStatus::getLabel($model->status) ?>
    </p>
    <ul>
        <li><?= Yii::t('app.f12.ecommerce', 'Fullname') ?>: <?= $model->fullname ?></li>
        <li><?= Yii::t('app.f12.ecommerce', 'Email') ?>: <?= Yii::$app->formatter->asEmail($model->email) ?></li>
        <li><?= Yii::t('app.f12.ecommerce', 'Phone') ?>: <?= PhoneFormatter::run($model->phone) ?></li>
        <li><?= Yii::t('app.f12.ecommerce', 'Total') ?>: <?= $model->total ?></li>
    </ul>
    <hr>
<?php endforeach; ?>

<p>
    Для перехода в админку, нажмите на
    <?= Html::a('эту ссылку', Yii::$app->urlManager->createAbsoluteUrl(['/shop/admin/order'])) ?>.
</p>
