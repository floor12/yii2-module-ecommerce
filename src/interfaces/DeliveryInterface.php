<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-07
 * Time: 10:31
 */

namespace floor12\ecommerce\interfaces;

interface DeliveryInterface
{
    public function __construct(int $cityFromId, float $wight);

    /** Возвращаем расчет стоимости товара
     * @return float
     */
    public function getPrice(): float;

}