<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class ParamType extends BaseEnum
{
    const LIST = 1;
    const STRING = 0;

    static public $list = [
        self::LIST => 'List',
        self::STRING => 'String',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}