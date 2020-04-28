<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.01.2017
 * Time: 22:12
 */

namespace floor12\ecommerce\components;

use floor12\ecommerce\assets\IconHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class TabWidget extends Widget
{
    public $linkPostfix;
    public $items;

    public function init()
    {
        $this->items = [
            [
                'name' => IconHelper::CART . Yii::t('app.f12.ecommerce', 'Orders'),
                'href' => Url::toRoute(['/shop/admin/order'])
            ],
            [
                'name' => IconHelper::BOXES . Yii::t('app.f12.ecommerce', 'Products'),
                'href' => Url::toRoute(['/shop/admin/product'])
            ],
            [
                'name' => IconHelper::CATS . Yii::t('app.f12.ecommerce', 'Categories'),
                'href' => Url::toRoute(['/shop/admin/category'])
            ],
            [
                'name' => IconHelper::PARAMS . Yii::t('app.f12.ecommerce', 'Product parameters'),
                'href' => Url::toRoute(['/shop/admin/parameter'])
            ],
            [
                'name' => IconHelper::PARAMS . Yii::t('app.f12.ecommerce', 'Parameters values'),
                'href' => Url::toRoute(['/shop/admin/value'])
            ],
            [
                'name' => IconHelper::WAREHOUSE . Yii::t('app.f12.ecommerce', 'Stocks'),
                'href' => Url::toRoute(['/shop/admin/stock'])
            ],
            [
                'name' => IconHelper::MONEY . Yii::t('app.f12.ecommerce', 'Payments'),
                'href' => Url::toRoute(['/shop/admin/payment']),
            ],
            [
                'name' => IconHelper::TAGS . Yii::t('app.f12.ecommerce', 'Discount groups'),
                'href' => Url::toRoute(['/shop/admin/discount-group']),
            ],
        ];
    }

    function run(): string
    {

        $active_flag = false;
        $nodes = [];

        if ($this->items) {

//            foreach ($this->items as $item) {
////                if (strpos($_SERVER['REQUEST_URI'], $item['href']) === 0)
//                var_dump($_SERVER['REQUEST_URI'] == $item['href']);
//                if ($_SERVER['REQUEST_URI'] == $item['href'])
//                    $active_flag = true;
//            }

            foreach ($this->items as $key => $item) {

                if (!isset($item['visible']) || $item['visible']) {

                    if ($_SERVER['REQUEST_URI'] == $item['href'])
                        $item['active'] = true;

                    $nodes[] = $this->render('tabWidget', ['item' => $item, 'linkPostfix' => $this->linkPostfix]);
                }
            }
        }
        return Html::tag('ul', implode("\n", $nodes), ['class' => 'nav nav-tabs ecommerce-tab-widget']);
    }
}
