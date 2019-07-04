<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 14:26
 *
 * @var $this \yii\web\View
 * @var $payLink string
 */

use yii\helpers\Html;

$this->title = Yii::t('app.f12.ecommerce', 'Payment failure');
$this->params['breadcrumbs'][] = $this->title;

?>


    <h1><?= $this->title ?></h1>
    <p><?= Yii::t('app.f12.ecommerce', 'An error occurred during the payment.') ?></p>

<?php if ($payLink) echo Html::a(Yii::t('app.f12.ecommerce', 'Retry payment'), $payLink, ['class' => 'btn btn-primary']) ?>