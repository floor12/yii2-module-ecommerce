<?php

namespace floor12\ecommerce\controllers\admin;


use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\filters\ProductVariationFilter;
use floor12\ecommerce\models\forms\ProductVariationForm;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\IndexAction;
use floor12\editmodal\ModalWindow;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * ProductVariationController implements the CRUD actions for ProductVariation model.
 */
class ProductVariationController extends Controller
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

    public function actionForm($product_id = 0, $id = 0)
    {
        if ($id) {
            if (!$model = ProductVariation::findOne((int)$id))
                throw new NotFoundHttpException();
        } else {
            $model = new ProductVariation();
            $model->product_id = (int)$product_id;
        }

        $form = new ProductVariationForm($model);
        $form->load(Yii::$app->request->post());
        if (Yii::$app->request->isPost && $form->save()) {
            return Yii::createObject(ModalWindow::class)
                ->info('Object saved', 1)
                ->reloadContainer('#items')
                ->hide()
                ->run();
        }
        return $this->renderAjax('_form', ['model' => $form]);

    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => ProductVariationFilter::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => ProductVariation::class,
                'message' => 'Объект удален'
            ],
        ];
    }


}
