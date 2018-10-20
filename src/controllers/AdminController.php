<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:30
 */

namespace floor12\ecommerce\controllers;

use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\EcItem;
use floor12\ecommerce\models\EcItemParam;
use floor12\ecommerce\models\filters\CategoryFilter;
use floor12\ecommerce\models\filters\ItemFilter;
use floor12\ecommerce\models\filters\OrderFilter;
use floor12\ecommerce\models\filters\ParamFilter;
use floor12\ecommerce\models\forms\ItemParamsForm;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\ModalWindow;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public $defaultAction = 'order';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Yii::$app->getModule('shop')->editRole],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'ite-delete' => ['delete'],
//                    'banner-delete' => ['delete'],
//                ],
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = Yii::$app->getModule('shop')->layout;
    }


    /** Displays order admin page
     * @return string
     */
    public function actionOrder()
    {
        $model = new OrderFilter();
        $model->load(Yii::$app->request->get());
        return $this->render('order', ['model' => $model]);
    }

    /** Displays item admin page
     * @return string
     */
    public function actionItem()
    {
        $model = new ItemFilter();
        $model->load(Yii::$app->request->get());
        return $this->render('item', ['model' => $model]);
    }

    /** Displays category admin page
     * @return string
     */
    public function actionCategory()
    {
        $model = new CategoryFilter();
        $model->load(Yii::$app->request->get());
        return $this->render('category', ['model' => $model]);
    }

    /** Displays param admin page
     * @return string
     */
    public function actionParam()
    {
        $model = new ParamFilter();
        $model->load(Yii::$app->request->get());
        return $this->render('param', ['model' => $model]);
    }

    /** Updating item parameters
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionItemParams($id)
    {
        $item = EcItem::findOne((int)$id);
        if (!$item)
            throw new NotFoundHttpException('Item is not found.');

        $model = new ItemParamsForm($item);
        if (Yii::$app->request->isPost &&
            $model->load(Yii::$app->request->post()) &&
            $model->saveParams()) {
            return Yii::createObject(ModalWindow::class, [])
                ->info(Yii::t('app.f12.ecommerce', 'Item parameters are saved.'), ModalWindow::TYPE_OK)
                ->hide()
                ->run();
        }

        return $this->renderAjax('form-item-param', ['model' => $model]);
    }

    /** Подключаем необходимые экшены для редактирования и удаления площадок и баннеров
     *  Для обеспечения этого функционала используем пакет floor12\editmodal для редактирования в модальном окне
     * @return array
     */
    public function actions(): array
    {
        return [
            'category-form' => [
                'class' => EditModalAction::class,
                'model' => EcCategory::class,
                'view' => 'form-category',
                'message' => Yii::t('app.f12.ecommerce', 'Category is saved.'),
                'viewParams' => [
                    'categories' => EcCategory::find()->dropbdown(),
                    'params' => EcItemParam::find()->dropbdown(),
                ],
            ],
            'category-delete' => [
                'class' => DeleteAction::class,
                'model' => EcCategory::class,
                'message' => Yii::t('app.f12.ecommerce', 'Category is deleted.')
            ],
            'param-form' => [
                'class' => EditModalAction::class,
                'model' => EcItemParam::class,
                'view' => 'form-param',
                'message' => Yii::t('app.f12.ecommerce', 'Parameter is saved.'),
                'viewParams' => [
                    'categories' => EcCategory::find()->dropbdown(),
                ],
            ],
            'param-delete' => [
                'class' => DeleteAction::class,
                'model' => EcItemParam::class,
                'message' => Yii::t('app.f12.ecommerce', 'Parameter is deleted.')
            ],
            'item-form' => [
                'class' => EditModalAction::class,
                'model' => EcItem::class,
                'view' => 'form-item',
                'message' => Yii::t('app.f12.ecommerce', 'Item is saved.'),
                'viewParams' => [
                    'categories' => EcCategory::find()->dropbdown(),
                ],
            ],
            'item-delete' => [
                'class' => DeleteAction::class,
                'model' => EcItem::class,
                'message' => Yii::t('app.f12.ecommerce', 'Item is deleted.')
            ]
        ];
    }
}