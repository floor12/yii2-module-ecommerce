<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class PriceType extends BaseEnum
{
    const FIRST = 0;
    const SECOND = 1;
    const THIRD = 2;

    static public $list = [
        self::FIRST => 'First',
        self::SECOND => 'Second',
        self::THIRD => 'Third',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}