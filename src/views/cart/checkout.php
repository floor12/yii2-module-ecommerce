<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 12:30
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\Order
 * @var $deliveries array
 */

use floor12\ecommerce\models\City;
use floor12\ecommerce\models\enum\PaymentType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

$this->title = Yii::t('app.f12.ecommerce', 'Order checkout');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('ecommerceAddressCheck(); cityReplace();');

$form = ActiveForm::begin([
    'enableClientValidation' => false
]);
?>


    <h1><?= $this->title ?></h1>

    <div class="row">

        <div class="col-md-7">
            <table class="table table-striped table-bordered table-cart">
                <tbody>
                <tr>
                    <th><?= Yii::t('app.f12.ecommerce', 'Item title') ?></th>
                    <th><?= Yii::t('app.f12.ecommerce', 'Parameters') ?></th>
                    <th><?= Yii::t('app.f12.ecommerce', 'Quantity') ?></th>
                    <th><?= Yii::t('app.f12.ecommerce', 'Price') ?></th>
                    <th><?= Yii::t('app.f12.ecommerce', 'Sum') ?></th>
                </tr>
                <?php if ($model->cart->rows) foreach ($model->cart->rows as $row) echo $this->render('_index', ['row' => $row, 'editable' => false]) ?>
                </tbody>
            </table>
            <div class="cart-total">
                <?= Yii::t('app.f12.ecommerce', 'Total') ?>: <span><?= $model->cart->total ?></span>
            </div>
        </div>

        <div class="col-md-5">

            <?= $form->field($model, 'fullname')
                ->textInput([
                    'data-description' => Yii::t('app.f12.ecommerce', 'Enter the name here.'),
                    'data-description-show' => 'true'
                ]); ?>

            <div class="row">
                <div class="col-xs-6">
                    <?= $form->field($model, 'email')->textInput([
                        'data-description' => Yii::t('app.f12.ecommerce', 'We need your email so that we can send the details of the order and contact you.'),
                        'data-description-show' => 'true'
                    ]); ?>
                </div>
                <div class="col-xs-6">
                    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                        'mask' => '+9 (999) 999-99-99'
                    ])->textInput([
                        'data-description' => Yii::t('app.f12.ecommerce', 'We need your phone number to clarify delivery issues.'),
                        'data-description-show' => 'true'
                    ]); ?>
                </div>
            </div>

            <?= $form->field($model, 'payment_type_id')->dropDownList(PaymentType::getAvailableTypes()) ?>

            <?= $form->field($model, 'delivery_type_id')->dropDownList($deliveries) ?>

            <div class="f12-ecommerce-address-section">

                <div class="row">
                    <div class="col-sm-4">
                        <?= $form->field($model, 'postcode')->textInput(['maxlength' => 6]) ?>
                    </div>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'city')
                            ->widget(Select2::class, [
                                    'data' => $model->city ? [$model->city => City::findOne($model->city)] : [],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => \yii\helpers\Url::toRoute(['/shop/cart/city']),
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                                    ],
                                ]
                            ) ?>
                        <?= $form->field($model, 'city_id')
                            ->label(false)
                            ->hiddenInput();
                        ?>
                    </div>
                </div>

                <?= $form->field($model, 'street') ?>


                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'building') ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'apartament') ?>
                    </div>
                </div>

            </div>

            <?= $form->field($model, 'comment')
                ->label(Yii::t('app.f12.ecommerce', 'Additional comment'))
                ->textarea([
                    'data-description' => Yii::t('app.f12.ecommerce', 'If you have additional comments or wished, please describe them here.'),
                    'data-description-show' => 'true'
                ]) ?>

            <?= Html::submitButton(Yii::t('app.f12.ecommerce', 'Send'), ['class' => 'btn btn-primary pull-right']) ?>

            <div id="f12-delivery-cost">
                <?= Yii::t('app.f12.ecommerce', 'Delivery cost') ?>:
                <span>0</span> <?= Yii::$app->getModule('shop')->currencyLabel ?>
            </div>


        </div>
    </div>

<?php ActiveForm::end() ?>