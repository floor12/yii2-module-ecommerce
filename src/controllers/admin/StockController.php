<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\filters\StockFilter;
use yii\web\Controller;


use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;


/**
 * StockController implements the CRUD actions for Stock model.
 */
class StockController extends Controller
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
                    'form' => ['GET','POST'],
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
    * {@inheritdoc}
    */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => StockFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Stock::class,
                'message' => 'Объект сохранен'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Stock::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
