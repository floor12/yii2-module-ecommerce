<?php


namespace floor12\ecommerce\logic;


use app\logic\balance\BalanceLogPush;
use app\modules\balance\models\Balance;
use app\modules\balance\models\BalanceType;
use app\modules\balance\models\Invoice;
use app\modules\balance\models\InvoiceStatus;
use floor12\ecommerce\components\AlfaClient;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\Payment;
use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\OrderStatus;
use Yii;
use yii\base\ErrorException;

class AlfaPaymentCheck
{
    /**
     * @var Invoice
     */
    protected $model;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var string
     */
    protected $password;

    /**
     * InvoiceCheck constructor.
     * @param Invoice $model
     * @param null $client
     * @throws ErrorException
     */
    public function __construct(Payment $model, $client = null)
    {
        if ($model->isNewRecord)
            throw new ErrorException('Этот счет еще не сохранен базе данных.');

        if (!$model->external_id)
            throw new ErrorException('Этот счет еще не зарегистрирован в банке и не имеет внешний индификатор.');

        if (empty(Yii::$app->getModule('shop')->payment_params[PaymentType::ALFABANK]['api_login']))
            throw new ErrorException('Alfa api login `api_login` not found in app config.');

        $this->login = Yii::$app->getModule('shop')->payment_params[PaymentType::ALFABANK]['api_login'];

        if (empty(Yii::$app->getModule('shop')->payment_params[PaymentType::ALFABANK]['api_pass']))
            throw new ErrorException('Alfa api password `api_pass` not found in app config.');

        $this->password = Yii::$app->getModule('shop')->payment_params[PaymentType::ALFABANK]['api_pass'];

        $this->model = $model;

        $this->client = $client;

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
     * @return bool
     */
    public function execute()
    {
        if ($this->model->status == PaymentStatus::SUCCESS)
            return true;

        $result = $this->client->getOrderStatus($this->model->external_id);

        $this->model->updated = time();
        $this->model->external_status = $result['orderStatus'];

        if (OrderStatus::isDeposited($result['orderStatus'])) {
            $this->model->status = PaymentStatus::SUCCESS;
            $this->model->order->status = \floor12\ecommerce\models\enum\OrderStatus::PAYED;
            $this->model->order->updated = time();
            $this->model->order->save();
        }

        if (OrderStatus::isDeclined($result['orderStatus']))
            $this->model->status = PaymentStatus::ERROR;

        return $this->model->save(true, ['status', 'updated', 'external_status']);
    }

}

