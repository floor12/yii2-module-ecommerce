<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\logic\OrderCreate;
use floor12\ecommerce\models\City;
use floor12\ecommerce\models\delivery\DeliverySdek;
use floor12\ecommerce\models\forms\CartForm;
use floor12\ecommerce\models\Order;
use Yii;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

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

        if (!sizeof($model->cart->rows))
            throw new BadRequestHttpException('Your cart is empty');

        if (Yii::$app->request->isPost) {
            if (Yii::createObject(OrderCreate::class, [$model, Yii::$app->request->post()])->execute())
                return $this->render('success');
        }

        return $this->render('checkout', ['model' => $model]);
    }

    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionCity($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, fullname AS text')
                ->from('ec_city')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::findOne($id)];
        }
        return $out;
    }

    /**
     * @return float|void
     */
    public function actionDeliveryCost()
    {
        $city_id = Yii::$app->request->getQueryParam('city_id');
        $weight = Yii::$app->request->getQueryParam('weight');
        if (!$city_id || !$weight)
            return;
        $model = new DeliverySdek($city_id, $weight);
        $model->loadData();
        return $model->getPrice();
    }
}