<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\logic\CheckoutTagRegister;
use floor12\ecommerce\logic\DeliveryCost;
use floor12\ecommerce\logic\OrderCreate;
use floor12\ecommerce\logic\OrderPurchaseTagRegister;
use floor12\ecommerce\logic\ParamProcessor;
use floor12\ecommerce\logic\PaymentCreate;
use floor12\ecommerce\models\City;
use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\forms\CartForm;
use floor12\ecommerce\models\Item;
use floor12\ecommerce\models\ItemParamValue;
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
        Yii::$app->getModule('shop');
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
                if ($model->payment_type_id == PaymentType::RECEIVING) {

                    if (Yii::$app->getModule('shop')->registerGoogleTagEvents)
                        Yii::createObject(OrderPurchaseTagRegister::class, [$model, Yii::$app->getView()])->register();

                    return $this->render('success');
                } else
                    $this->redirect(['/shop/cart/pay', 'order_id' => $model->id]);
        }


        if (Yii::$app->getModule('shop')->registerGoogleTagEvents)
            Yii::createObject(CheckoutTagRegister::class, [$model->cart, Yii::$app->getView()])->register();

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

    public function actionOptions($item_id)
    {
        $params = Yii::$app->request->get('params');

        if (!$params)
            return;

        foreach ($params as $param_id => $param_value) {
            $items[] = ItemParamValue::find()
                ->distinct()
                ->select('item_id')
                ->where([
                    'parent_item_id' => $item_id,
                    'param_id' => $param_id,
                    'value' => $param_value])
                ->column();
        }

        $result = $items[0];

        if (sizeof($items) > 1)
            foreach ($items as $key => $options_ids) {
                if (!$key || !$options_ids)
                    continue;
                $result = array_uintersect($result, $options_ids, "strcasecmp");
            }


        switch (sizeof($result)) {
            case 1:
                $id = $result[array_key_first($result)];
                $item = Item::findOne($id);
                if ($item->available) {
                    $cart = new CartForm();
                    $price = $cart->getPrice($item);
                    $ret = [
                        'status' => 0,
                        'option_id' => $id,
                        'price' => $price,
                        'message' => "Стоимость: " . $price . " " . Yii::$app->getModule('shop')->currencyLabel,
                        'gtagData' => [
                            'id' => $id,
                            'name' => $item->title,
                            'price' => $item->price,
                            'category' => $item->categories ? $item->categories[0]->title : NULL,
                            'quantity' => 1,
                            'variant' => Yii::createObject(ParamProcessor::class, [$item])->getParamsInString()
                        ]
                    ];
                } else
                    $ret = [
                        'status' => 1,
                        'message' => 'нет в наличии'
                    ];
                break;
            case 0:
                $ret = [
                    'status' => 1,
                    'message' => 'нет в наличии'
                ];
                break;
            default:
                $ret = [
                    'status' => 2,
                    'message' => 'уточните параметры'
                ];
        }

        return json_encode($ret);
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