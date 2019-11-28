<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-01-22
 * Time: 20:28
 */

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\delivery\DeliverySdek;
use floor12\ecommerce\models\enum\DeliveryType;
use Yii;
use yii\web\NotFoundHttpException;

class DeliveryCost
{
    protected $_type_id;

    protected $_deliveries = [];

    protected $_params = [];

    protected $_currentDelivery;

    /**
     * DeliveryCost constructor.
     * @param int $type_id
     * @param array $params
     */
    public function __construct(int $type_id, array $params)
    {
        $this->_type_id = $type_id;

        $this->_params = $params;

        $this->_deliveries = Yii::$app->getModule('shop')->deliveryTypes;

        if (!isset($this->_deliveries[(int)$type_id]))
            throw new NotFoundHttpException('Delivery type is not found.');

        $this->_currentDelivery = $this->_deliveries[$type_id];

    }

    /**
     * @return int
     */
    public function getPrice()
    {
        switch ($this->_currentDelivery['type']) {

            case DeliveryType::PICK_UP:
                return 0;

            case DeliveryType::SIMPLE:
                return $this->_currentDelivery['price'];

            case DeliveryType::SDEK:
                if (empty($this->_params['city_id']) || empty($this->_params['weight']))
                    return 0;
                $delivery = new DeliverySdek($this->_params['city_id'], $this->_params['weight']);
                $delivery->loadData();
                return $delivery->getPrice();
        }
    }
}