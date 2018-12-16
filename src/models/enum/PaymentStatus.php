<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class PaymentStatus extends BaseEnum
{
    const NEW = 0;
    const IN_PROCESS = 1;
    const SUCCESS = 2;
    const ERROR = 3;

    static public $list = [
        self::NEW => 'new',
        self::IN_PROCESS => 'in process',
        self::SUCCESS => 'success',
        self::ERROR => 'error',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}