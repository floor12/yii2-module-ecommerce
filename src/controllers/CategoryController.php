<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\models\Item;
use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\filters\ItemFrontendFilter;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CategoryController extends Controller
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = Yii::$app->getModule('shop')->layout;
    }

    /**
     * @param $page
     * @param int $category_id
     * @return string
     */
    public function actionIndex($page, $category_id = 0)
    {
        $model = new ItemFrontendFilter(['category_id' => $category_id]);
        $model->load(Yii::$app->request->get());
        return $this->render(Yii::$app->getModule('shop')->viewIndex, ['model' => $model]);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionItem($id)
    {

        $model = Item::findOne($id);

        if (!$model)
            throw new NotFoundHttpException('Item is not found.');


        if ($model->status == Status::DISABLED && !Yii::$app->getModule('shop')->adminMode())
            throw new ForbiddenHttpException('Item is disabled.');

        return $this->render(Yii::$app->getModule('shop')->viewItem, ['model' => $model]);
    }

}