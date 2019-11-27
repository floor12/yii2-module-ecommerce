<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:53
 */

namespace floor12\ecommerce\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class SortVariations extends BaseEnum
{
    const SORT_NEW = 0; // По категориям а потом внутри них по дате добавления товара
    const SORT_PRICE_ASC = 1;
    const SORT_PRICE_DESC = 2;


    static public $list = [
        self::SORT_NEW => 'New first',
        self::SORT_PRICE_ASC => 'Cheaper first',
        self::SORT_PRICE_DESC => 'Expensive first',
    ];

    public static $messageCategory = 'app.f12.ecommerce';

}