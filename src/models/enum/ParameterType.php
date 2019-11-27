<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class ParameterType extends BaseEnum
{
    const CHECKBOX = 1;
    const SLIDER = 0;

    static public $list = [
        self::CHECKBOX => 'Checkboxes',
        self::SLIDER => 'Slider',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}