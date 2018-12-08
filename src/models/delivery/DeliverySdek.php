<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-07
 * Time: 10:49
 */

namespace floor12\ecommerce\models\delivery;


use floor12\ecommerce\interfaces\DeliveryInterface;
use Yii;
use yii\base\ErrorException;

class DeliverySdek implements DeliveryInterface
{

    public $tariffId;
    public $weight;
    public $width;
    public $height;
    public $length;
    public $cityFromId;
    public $cityToId;
    public $date;
    public $result = [];
    public $jsonUrl = 'http://api.cdek.ru/calculator/calculate_price_by_json_request.php';
    public $price = 0;

    public function __construct(int $cityToId, float $wight)
    {
        $this->cityToId = $cityToId;
        $this->cityFromId = Yii::$app->getModule('shop')->sdekCityFromId;
        $this->weight = $wight;
        $this->width = Yii::$app->getModule('shop')->defaultDeliveryWidth;
        $this->height = Yii::$app->getModule('shop')->defaultDeliveryHeight;
        $this->length = Yii::$app->getModule('shop')->defaultDeliveryDepth;
        $this->date = date('Y-m-d');
        $this->tariffId = 11;


    }

    public function loadData()
    {
        $data = [
            'version' => '1.0',
            'dateExecute' => $this->date,
            'receiverCityId' => (string)$this->cityToId,
            'senderCityId' => (string)$this->cityFromId,
            'tariffId' => (string)$this->tariffId,
            'goods' => [
                [
                    'weight' => (string)$this->weight,
                    'length' => (string)$this->length,
                    'width' => (string)$this->width,
                    'height' => (string)$this->height,
                ]
            ]
        ];

        $this->result = $this->_getRemoteData($data);
    }


    public function getPrice(): float
    {
        return $this->price;
    }

    private function _getRemoteData($data)
    {
        $json = json_encode($data);
        $params = http_build_query(['json' => $json]);
        $ch = curl_init();
        $url = $this->jsonUrl . '?' . $params;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->result = json_decode(curl_exec($ch));
        curl_close($ch);
        if (!isset($this->result->result->price))
            throw new ErrorException("Error in price request: {$this->result->error[0]->text}");
        $this->price = $this->result->result->price;
    }

}