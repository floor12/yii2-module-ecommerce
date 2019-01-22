<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\logic\DeliveryCost;
use floor12\ecommerce\logic\OrderCreate;
use floor12\ecommerce\logic\PaymentCreate;
use floor12\ecommerce\models\City;
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
        $model = new Order();
        $model->cart = new CartForm();
        $deliveries = [];

        $model->cart->cleanNotAvailble();

        if (!sizeof($model->cart->rows))
            throw new BadRequestHttpException('Your cart is empty');


        if (Yii::$app->getModule('shop')->deliveryTypes)
            $deliveries = array_map(function ($delivery) {
                return $delivery['name'];
            }, Yii::$app->getModule('shop')->deliveryTypes);


        if (Yii::$app->request->isPost) {
            if (Yii::createObject(OrderCreate::class, [$model, Yii::$app->request->post()])->execute())
                if ($model->payment_type_id == PaymentType::RECEIVING)
                    return $this->render('success');
                else
                    $this->redirect(['/shop/cart/pay', 'order_id' => $model->id]);
        }

        return $this->render('checkout', ['model' => $model, 'deliveries' => $deliveries]);
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
     * @param $type_id
     * @return float|void
     */
    public function actionDeliveryCost($type_id)
    {
        $pricer = new DeliveryCost((int)$type_id, Yii::$app->request->getQueryParams());
        return $pricer->getPrice();
    }
}