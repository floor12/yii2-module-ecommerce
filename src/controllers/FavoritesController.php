<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\Item;
use yii\web\Controller;

class FavoritesController extends Controller
{


    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderAjax('index');
    }

    public function actionItem($id)
    {
        $model = Item::findOne((int)$id);
        if (!$model || $model->status != Status::ACTIVE)
            return;

        return $this->renderAjax('@vendor/floor12/yii2-module-ecommerce/src/views/category/_index', ['model' => $model]);
    }

}