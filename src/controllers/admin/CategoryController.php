<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\filters\CategoryFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'order' => ['GET'],
                    'form' => ['GET', 'POST'],
                    'delete' => ['DELETE'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = \Yii::$app->getModule('shop')->layout;
    }

    /**
     * @param $id
     * @param $direction
     * @throws NotFoundHttpException
     */
    public function actionOrder($id, $direction)
    {
        $model = Category::findOne((int)$id);
        if (!$model)
            throw new NotFoundHttpException();
        $model->changeSorting($direction);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => CategoryFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Category::class,
                'message' => 'Объект сохранен',
                'viewParams' => [
                    'parameters' => Parameter::find()->dropdown(),
                    'categories' => Category::find()->dropdown(),
                ]
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Category::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
