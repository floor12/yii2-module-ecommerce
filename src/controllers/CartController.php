<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\logic\OrderCreate;
use floor12\ecommerce\logic\PaymentCreate;
use floor12\ecommerce\models\City;
use floor12\ecommerce\models\delivery\DeliverySdek;
use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\forms\CartForm;
use floor12\ecommerce\models\Order;
use Yii;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
        //Yii::$app->getModule('shop');
        $model = new Order();
        $model->cart = new CartForm();

        $model->cart->cleanNotAvailble();
        //var_dump($model->cart->rows);

        if (!sizeof($model->cart->rows))
            throw new BadRequestHttpException('Your cart is empty');

        if (Yii::$app->request->isPost) {
            if (Yii::createObject(OrderCreate::class, [$model, Yii::$app->request->post()])->execute())
                if ($model->payment_type_id == PaymentType::RECEIVING)
                    return $this->render('success');
                else
                    $this->redirect(['/shop/cart/pay', 'order_id' => $model->id]);
        }

        return $this->render('checkout', ['model' => $model]);
    }

    /**
     * @return string
     */
    public function actionPay($order_id)
    {
        $model = Order::findOne((int)$order_id);
        if (!$model)
            throw new NotFoundHttpException('Order not found');

        Yii::createObject(PaymentCreate::class, [$model])->execute();


        if ($model->payment_type_id == PaymentType::CLOUDPAYMENTS) {
            $publicKey = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_id'];
            $privateKey = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['api_pass'];
            $currency = Yii::$app->getModule('shop')->payment_params[PaymentType::CLOUDPAYMENTS]['currency'];
            return $this->render('pay_cloudpayments', [
                'model' => $model,
                'publicKey' => $publicKey,
                'currency' => $currency,
            ]);
        }

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