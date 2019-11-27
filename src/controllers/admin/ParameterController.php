<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\filters\ParameterFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * ParameterController implements the CRUD actions for Parameter model.
 */
class ParameterController extends Controller
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
        $this->layout = Yii::$app->getModule('shop')->layout;
    }

    /**
     * @param $id
     * @param $direction
     * @throws NotFoundHttpException
     */
    public function actionOrder($id, $direction)
    {
        $model = Parameter::findOne((int)$id);
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
                'model' => ParameterFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Parameter::class,
                'message' => 'Объект сохранен',
                'viewParams' => [
                    'categories' => Category::find()->dropdown()
                ]
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Parameter::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
