<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 12:30
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Order
 * @var $publicKey string
 * @var $currency string
 */

$this->registerJsFile('https://widget.cloudpayments.ru/bundles/cloudpayments', ['position' => \yii\web\View::POS_HEAD]);
$this->title = Yii::t('app.f12.ecommerce', 'Order payment');
$this->params['breadcrumbs'][] = $this->title;

$paymentDescription = Yii::$app->getModule('shop')->paymentDescription;

$js = <<< JS
this.pay = function () {
    var widget = new cp.CloudPayments();
    widget.charge({ 
            publicId: '$publicKey',
            description: '$paymentDescription',
            amount: $model->total, 
            currency: '$currency',
            invoiceId: {$model->payments[0]->id}, 
            accountId: '$model->email',
        },
        function (options) { 
            $('.alert-success').show();
            $('.payment-go').hide();
            $('.alert-warning').hide();
        },
        function (reason, options) {
             $('.alert-warning').show();
        });
};
JS;

$this->registerJs($js, \yii\web\View::POS_HEAD);
?>


<h1><?= Yii::t('app.f12.ecommerce', 'Payment with Cloud Payments') ?></h1>

<div class="alert alert-warning" style="display: none">
    <?= Yii::t('app.f12.ecommerce', 'The payment is not completed') ?>
</div>

<div class="alert alert-success" style="display: none">
    <?= Yii::t('app.f12.ecommerce', 'The payment is completed successfully') ?>
</div>

<div class="payment-go">
    <p>
        <?= Yii::t('app.f12.ecommerce', 'Push to begin payment process') ?>.
    </p>

    <a onclick="pay()" class="btn btn-default"><?= Yii::t('app.f12.ecommerce', 'Pay') ?></a>
</div>