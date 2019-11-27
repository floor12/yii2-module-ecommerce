<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\filters\ProductFilter;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                'model' => ProductFilter::class,
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Product::class,
                'message' => 'Объект сохранен',
                'viewParams' => [
                    'categories' => Category::find()->dropdown()
                ]
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Product::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
