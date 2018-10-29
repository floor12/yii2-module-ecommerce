<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\logic\OrderCreate;
use floor12\ecommerce\models\forms\CartForm;
use floor12\ecommerce\models\Order;
use Yii;
use yii\web\Controller;

class CartController extends Controller
{

    /** Просмотр и редактирование корзины в модальном окне
     * @return string
     */
    public function actionIndex()
    {
        $model = new CartForm();
        return $this->renderAjax('index', ['model' => $model]);
    }

    /** Странница офорсления заказа
     * @return string
     */
    public function actionCheckout()
    {
        $model = new Order();
        $model->cart = new CartForm();

        if (Yii::$app->request->isPost) {
            if (Yii::createObject(OrderCreate::class, [$model, Yii::$app->request->post()])->execute())
                return $this->render('success');
        }

        return $this->render('checkout', ['model' => $model]);
    }
}