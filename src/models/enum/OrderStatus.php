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
    const IN_PROGRESS = 2;
    const DONE = 3;

    static public $list = [
        self::READY => 'Ready to order',
        self::ORDERED => 'Ordered',
        self::IN_PROGRESS => 'In progress',
        self::DONE => 'Done',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}