<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\filters\ParameterValueFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * ParameterValueController implements the CRUD actions for ParameterValue model.
 */
class ValueController extends Controller
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
        $model = ParameterValue::findOne((int)$id);
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
                'model' => ParameterValueFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => ParameterValue::class,
                'message' => 'Объект сохранен'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => ParameterValue::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
