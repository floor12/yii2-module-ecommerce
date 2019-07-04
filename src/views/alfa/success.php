<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 14:26
 *
 * @var $this \yii\web\View
 */

$this->title = Yii::t('app.f12.ecommerce', 'Thanks for purchase');
$this->params['breadcrumbs'][] = $this->title;

?>


<h1><?= Yii::t('app.f12.ecommerce', 'Payment success') ?></h1>
<p><?= Yii::t('app.f12.ecommerce', 'Our managers will contact you in the nearest future to confirm your order.'); ?></p>
