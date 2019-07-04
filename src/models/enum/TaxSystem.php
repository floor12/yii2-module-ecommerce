<?php


namespace floor12\ecommerce\models\enum;


class TaxSystem
{
    const MAIN = 0; // общая
    const UPROSHOEN = 1; // упрощенная
    const EDIN_DOHOD = 2; // единый, доход
    const EDIN_DOHOD_RASHOD = 3; // единый, доход минус расход
    const SELHOZ = 4; // единый сельскохозяйственный налог
    const PATENT = 5; //патентная система налогообложения.

}