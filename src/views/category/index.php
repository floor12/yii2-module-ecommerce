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

use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<h1><?= $model->category_title ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'GET'
]);
?>

<div class="item-filter">

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
