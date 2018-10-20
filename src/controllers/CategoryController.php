<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\models\filters\ItemFrontendFilter;
use Yii;
use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionIndex($page, $category_id = 0)
    {
        $model = new ItemFrontendFilter(['category_id' => $category_id]);

        return $this->render(Yii::$app->getModule('shop')->viewIndex, ['model' => $model]);
    }

}