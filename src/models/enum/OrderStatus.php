<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class OrderStatus extends BaseEnum
{
    const READY = 0;
    const ORDERED = 1;
    const PAYMENT_EXPECTS = 2;
    const PAYED = 3;
    const IN_PROGRESS = 4;
    const DONE = 5;
    const IN_DELIVERY = 6;
    const CANCELED = 7;

    static public $list = [
        self::READY => 'Ready to order',
        self::ORDERED => 'Ordered',
        self::PAYMENT_EXPECTS => 'Expects payment',
        self::PAYED => 'Payed',
        self::IN_PROGRESS => 'In progress',
        self::DONE => 'Done',
        self::IN_DELIVERY => 'In delivery',
        self::IN_DELIVERY => 'Canceled',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}
