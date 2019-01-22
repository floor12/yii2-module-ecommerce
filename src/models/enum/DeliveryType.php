<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class DeliveryType extends BaseEnum
{
    const PICK_UP = 0;
    const SIMPLE = 1;
    const SDEK = 2;

    static public $list = [
        self::PICK_UP => 'Pick up',
        self::SIMPLE => 'Simple',
        self::SDEK => 'TK SDEK',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}