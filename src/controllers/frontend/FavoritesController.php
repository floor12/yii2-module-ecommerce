<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers\frontend;


use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\Status;
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

    public function actionProduct($id)
    {
        $model = Product::findOne((int)$id);
        if (!$model || $model->status != Status::ACTIVE)
            return;

        return $this->renderAjax('@vendor/floor12/yii2-module-ecommerce/src/views/frontend/product/_index', ['model' => $model]);
    }

}