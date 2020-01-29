<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:26
 *
 * @var $this \yii\web\View
 * @var $model \floor12\ecommerce\models\filters\FrontendProductFilter
 *
 */

use kartik\form\ActiveForm;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->registerJs('pageSize = ' . Yii::$app->getModule('shop')->itemPerPage, View::POS_BEGIN);


?>

<h1><?= $model->pageTitle ?></h1>
<div class="row">
    <div class="col-md-3">
        <div class="item-filter">

            <?php $form = ActiveForm::begin([
                'method' => 'GET',
                'enableClientValidation' => false,
                'id' => 'f12-eccomerce-product-filter',
                'options' => ['data-container' => '#products'],
            ]);
            ?>

            <?= $form->field($model, 'priceMinValue')->label(false)->hiddenInput() ?>
            <?= $form->field($model, 'priceMaxValue')->label(false)->hiddenInput() ?>


            <?= $form->field($model, 'price')
                ->label(false)
                ->widget(\floor12\ecommerce\components\PriceSlider::class, [
                    'lowerValueContainerId' => 'frontendproductfilter-priceminvalue',
                    'upperValueContainerId' => 'frontendproductfilter-pricemaxvalue',
                    'pluginOptions' => [
                        'start' => [$model->priceMinValue, $model->priceMaxValue],
                        'connect' => true,
                        'tooltips' => true,
                        'pips' => [
                            'mode' => 'steps',
                            'stepped' => true,
                            'density' => 4,
                            'format' > [
                                'decimals' => 0,
                            ],
                        ],
                        'range' => [
                            'min' => $model->priceMin,
                            'max' => $model->priceMax
                        ]
                    ]
                ]);
            ?>


            <?= $form->field($model, "sort")
                ->label(Yii::t('app.f12.ecommerce', 'Sorting'))
                ->widget(\yii\bootstrap\ToggleButtonGroup::class, [
                    'items' => \floor12\ecommerce\models\enum\SortVariations::listData(),
                    'type' => 'radio',
                    'options' => ['class' => 'btn-group'],
                    'labelOptions' => ['class' => 'btn btn-default']

                ]); ?>

            <?= $form->field($model, "category_id")
                ->label(Yii::t('app.f12.ecommerce', 'Categories'))
                ->widget(\yii\bootstrap\ToggleButtonGroup::class, [
                    'items' => $model->category_list,
                    'type' => 'radio',
                    'options' => ['class' => 'btn-group'],
                    'labelOptions' => ['class' => 'btn btn-default']

                ]); ?>

            <?php foreach ($model->parameters as $parameterId => $parameter) {
                if (!empty($model->data[$parameterId]))
                    echo $form->field($model, "values[{$parameterId}]")
                        ->label($model->parameters[$parameterId]->title)
                        ->checkboxButtonGroup($model->data[$parameterId]);
            } ?>

            <?php if ($model->showDiscountOption): ?>
                <div data-toggle="buttons">
                    <label class="btn btn-default btn-sm">
                        <input type="checkbox"
                               autocomplete="off" <?= $model->discount ? "checked=checked" : NULL ?>
                               name="ItemFrontendFilter[discount]"> <?= Yii::t('app.f12.ecommerce', 'only discounted goods') ?>
                    </label>
                </div>
            <?php endif; ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>
    <div class="col-md-9">
        <div>
            <?php Pjax::begin(['id' => 'products']) ?>

            <div class="f12-ec-items">
                <?= ListView::widget([
                    'dataProvider' => $model->dataProvider(),
                    'layout' => '<div class="row">{items}</div>',
                    'itemView' => Yii::$app->getModule('shop')->viewIndexListItem
                ]) ?>

                <?php if ($model->dataProvider()->totalCount > $model->dataProvider()->pagination->pageSize) : ?>
                    <button onclick="f12Listview.next();" class="load-more"><?= Yii::t('app.f12.ecommerce', 'show more') ?></button>
                <?php endif; ?>

                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->params['currentPage']->content ?>
