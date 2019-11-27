<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\logic\discounts\DiscountGroupUpdate;
use floor12\ecommerce\models\entity\DiscountGroup;
use floor12\ecommerce\models\filters\DiscountGroupFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * DiscountGroupController implements the CRUD actions for DiscountGroup model.
 */
class DiscountGroupController extends Controller
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => DiscountGroupFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => DiscountGroup::class,
                'logic' => DiscountGroupUpdate::class,
                'message' => 'Объект сохранен'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => DiscountGroup::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
