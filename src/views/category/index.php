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

use floor12\ecommerce\components\ParameterInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<h1><?= $model->category_title ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'GET',
    'id' => 'f12-eccomerce-item-filter',
    'options' => ['data-container' => '#items'],
]);
?>

<div class="item-filter">
    <div class="">
        <?php
        foreach ($model->params as $parameter)
            echo Html::tag('div', ParameterInput::widget([
                'category' => $model->getCategory(),
                'parameter' => $parameter,
                'form' => $form,
                'filter' => $model
            ]), ['class' => ''])
        ?>
    </div>
</div>
<?php ActiveForm::end() ?>




<?php Pjax::begin(['id' => 'items']) ?>

<div class="f12-ec-items">
    <?= ListView::widget([
        'dataProvider' => $model->dataProvider(),
        'layout' => '<div class="row">{items}</div>{pager}{summary}',
        'itemView' => Yii::$app->getModule('shop')->viewIndexListItem
    ]) ?>

    <?php Pjax::end() ?>
</div>
