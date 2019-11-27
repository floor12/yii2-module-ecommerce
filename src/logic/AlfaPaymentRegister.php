<?php


namespace floor12\ecommerce\logic;


use floor12\ecommerce\components\AlfaClient;
use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\entity\Payment;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\enum\PaymentType;
use Voronkovich\SberbankAcquiring\Client;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class AlfaPaymentRegister
{

    /**
     * @var
     */
    protected $response;
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var Payment
     */
    protected $payment;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $returnUrl;
    /**
     * @var string
     */
    protected $failUrl;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var integer
     */
    protected $taxSystem;
    /**
     * @var integer
     */
    protected $taxType;

    /**
     * @var string
     */
    protected $formUrl;

    /**
     * AlfaPaymentRegister constructor.
     * @param Order $order
     * @throws ErrorException
     */
    public function __construct(Order $order)
    {
        $moduleParams = Yii::$app->getModule('shop')->payment_params[PaymentType::ALFABANK];

        if (empty($moduleParams['api_login']))
            throw new ErrorException('Alfa api login `api_login` not found in app config.');

        if (empty($moduleParams['api_pass']))
            throw new ErrorException('Alfa api password `api_pass` not found in app config.');

        if (!isset($moduleParams['tax_system']))
            throw new ErrorException('Tax system `tax_system` not found in app config.');

        if (!isset($moduleParams['tax_type']))
            throw new ErrorException('Tax type `tax_type` not found in app config.');

        $this->taxType = $moduleParams['tax_type'];

        $this->taxSystem = $moduleParams['tax_system'];

        $this->login = $moduleParams['api_login'];

        $this->password = $moduleParams['api_pass'];

        $this->order = $order;

        if (empty($order->payments[0]))
            throw new ErrorException('This order has no linked payments');

        $this->payment = $order->payments[0];

        $this->returnUrl = Url::to('/shop/alfa/success', true);
        $this->failUrl = Url::to('/shop/alfa/failure', true);

        if (!$this->client) {
            $this->client = new AlfaClient([
                'apiUri' => 'https://web.rbsuat.com',
                'password' => $this->password,
                'userName' => $this->login,
                'endpointPrefix' => '/ab/rest/',
            ]);
        }
    }


    /**
     * @throws ErrorException
     */
    public function execute()
    {
        if ($this->payment->external_id) {
            $this->formUrl = $this->payment->form_url;
            return true;
        }

        $data = [
            'taxSystem' => $this->taxSystem,
            'orderBundle' => $this->getOrderBundle(),
            'failUrl' => $this->failUrl
        ];

        $this->response = $this->client->registerOrder($this->payment->id, $this->order->total * 100, $this->returnUrl, $data);

        if (empty($this->response['formUrl']))
            throw new ErrorException('FormUrl is empty');

        $this->payment->external_id = $this->response['orderId'];
        $this->payment->form_url = $this->response['formUrl'];
        $this->payment->status = PaymentStatus::IN_PROCESS;

        if (!$this->payment->save())
            throw new BadRequestHttpException('Order payment validation error:' . print_r($this->payment->errors, 1) . print_r($this->response, 1));

        $this->formUrl = $this->payment->form_url;
        return true;
    }

    /**
     * @return array
     */
    protected function getOrderBundle()
    {
        $orderBundle = [
            'orderCreationDate' => date('Y-m-d\TH:i:s', $this->order->created),
            'customerDetails' => ['email' => $this->order->email],
            'cartItems' => [
                'items' => [],
            ]
        ];

        foreach ($this->order->orderItems as $position => $orderItem)
            $orderBundle['cartItems']['items'][] = [
                'positionId' => $position + 1,
                'name' => $orderItem->item->title,
                'quantity' => ['value' => $orderItem->quantity, 'measure' => 'шт'],
                'itemPrice' => $orderItem->price * 100,
                'itemAmount' => $orderItem->sum * 100,
                'itemCode' => $orderItem->item_id,
                'tax' => ['taxType' => $this->taxType],
            ];

        return $orderBundle;
    }

    /**
     * @return string
     */
    public function getFormUrl()
    {
        return $this->formUrl;
    }
}
