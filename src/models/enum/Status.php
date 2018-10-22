<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class Status extends BaseEnum
{
    const ACTIVE = 0;
    const DISABLED = 1;

    static public $list = [
        self::ACTIVE => 'Active',
        self::DISABLED => 'Disabled',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}