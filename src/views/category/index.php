<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:26
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\ItemFrontendFilter
 *
 */

use app\components\YouParameterInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii2mod\slider\IonSlider;

?>

    <h1><?= $model->category_title ?></h1>
    <div class="row">
        <div class="col-md-3">
            <div class="item-filter">

                <?php $form = ActiveForm::begin([
                    'method' => 'GET',
                    'id' => 'f12-eccomerce-item-filter',
                    'options' => ['data-container' => '#items'],
                ]);
                ?>

                <?= $form->field($model, 'filter')
                    ->label(false)
                    ->textInput(['placeholder' => Yii::t('app.f12.ecommerce', 'product filter...')]) ?>

                <div class="item-filter-sliders <?= (sizeof($model->slider_params) % 2 == 0) ? 'item-filter-sliders-wide' : NULL ?>">

                    <?= Html::tag('div', $form
                        ->field($model, "price")
                        ->widget(IonSlider::class, [
                            'pluginOptions' => [
                                'keyboard' => false,
                                'grid_num' => 10,
                                'postfix' => ' ' . Yii::$app->getModule('shop')->currencyLabel,
                                'min' => $model->price_min,
                                'max' => $model->price_max,
                                'grid' => true,
                                'force_edges' => true,
                                'type' => 'double',
                                'step' => 1,
                            ]
                        ])
                        ->label(Yii::t('app.f12.ecommerce', 'Price')), ['class' => 'f12-ecommerce-slider-block']);
                    ?>

                    <?php foreach ($model->slider_params as $parameter)
                        echo YouParameterInput::widget([
                            'category' => $model->getCategory(),
                            'parameter' => $parameter,
                            'form' => $form,
                            'filter' => $model
                        ]) ?>

                    <div class="clearfix"></div>
                </div>
                <div class="item-filter-checkboxes">
                    <?php foreach ($model->checkbox_params as $parameter)
                        echo YouParameterInput::widget([
                            'category' => $model->getCategory(),
                            'parameter' => $parameter,
                            'form' => $form,
                            'filter' => $model
                        ]) ?>


                    <?php if ($model->showDiscountOption): ?>
                        <div data-toggle="buttons">
                            <label class="btn btn-default btn-sm">
                                <input type="checkbox"
                                       autocomplete="off" <?= $model->discount ? "checked=checked" : NULL ?>
                                       name="ItemFrontendFilter[discount]"> <?= Yii::t('app.f12.ecommerce', 'only discounted goods') ?>
                            </label>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
        <div class="col-md-9">
            <div>
                <?php Pjax::begin(['id' => 'items']) ?>

                <div class="f12-ec-items">
                    <?= ListView::widget([
                        'dataProvider' => $model->dataProvider(),
                        'layout' => '<div class="row">{items}</div>{pager}{summary}',
                        'itemView' => Yii::$app->getModule('shop')->viewIndexListItem
                    ]) ?>

                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>

<?= $this->params['currentPage']->content ?>