<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\components\OrderReportXls;
use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\filters\OrderFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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

    public function actionReport()
    {
        $model = new OrderFilter();
        $model->load(Yii::$app->request->get());
        $reportGenerator = new OrderReportXls($model);
        $path = $reportGenerator->generateXlsx();
        Yii::$app->response->sendFile($path);
        unlink($path);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => OrderFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Order::class,
                'message' => 'Заказ сохранен',
                'scenario' => Order::SCENARIO_ADMIN
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Order::class,
                'message' => 'Заказ удален'
            ],
        ];
    }


}
