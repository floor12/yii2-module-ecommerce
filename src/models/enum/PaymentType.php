<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use Yii;
use yii2mod\enum\helpers\BaseEnum;

class PaymentType extends BaseEnum
{
    const RECEIVING = 0;
    const CLOUDPAYMENTS = 1;

    static public $list = [
        self::RECEIVING => 'Payment upon receipt',
        self::CLOUDPAYMENTS => 'With Cloud payments',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

    /**
     * @return array
     */
    public static function getAvailableTypes()
    {
        $available_types = [];
        if (Yii::$app->getModule('shop')->payment_types)
            foreach (Yii::$app->getModule('shop')->payment_types as $type_id)
                $available_types[$type_id] = self::getLabel($type_id);
        return $available_types;
    }

}