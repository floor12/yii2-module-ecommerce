<?php


namespace floor12\ecommerce\logic;


use app\modules\balance\models\Invoice;
use app\modules\balance\models\InvoiceStatus;
use floor12\ecommerce\models\enum\PaymentType;
use floor12\ecommerce\models\Order;
use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\Currency;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Url;

class AlfaPaymentRegister
{

    const ENDPOINT_REGISTER = 'https://server/payment/rest/';
    /**
     * @var Invoice
     */
    protected $model;
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
     * InvoiceRegister constructor.
     * @param Invoice $model
     * @param null $client
     * @throws ErrorException
     */
    public function __construct(Invoice $model, $client = null)
    {
        if ($model->isNewRecord)
            throw new ErrorException('Этот счет еще не сохранен базе данных.');

        if ($model->status != InvoiceStatus::NEW)
            throw new ErrorException('Неверный статус счета.');

        if ($model->external_order_id)
            throw new ErrorException('Этот счет уже зарегистрирован в банке и имеет внешний индификатор.');


        $this->returnUrl = Url::to('/balance/inflow/success', true);
        $this->failUrl = Url::to('/balance/inflow/failure', true);

        $this->model = $model;

        $this->client = $client;

        if (!$this->client) {
            $this->client = new Client([
                'token' => Yii::$app->params['sber.token'],
                'apiUri' => Yii::$app->params['sber.apiUri'],
            ]);
        }
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $result = $this->client->registerOrder($this->model->id, $this->model->amount * 100, $this->returnUrl, [
            'currency' => Currency::RUB,
            'failUrl' => $this->failUrl
        ]);

        $this->model->external_order_id = $result['orderId'];
        $this->model->form = $result['formUrl'];
        $this->model->updated = time();

        return $this->model->save(true, ['external_order_id', 'form', 'updated']);
    }
}
