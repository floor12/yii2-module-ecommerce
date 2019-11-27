<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 18:07
 */

namespace floor12\ecommerce\assets;

use yii\web\AssetBundle;

class EcommerceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-module-ecommerce/src/assets';

    public $css = [
        'zoomer.css',
        'ecommerce.css'
    ];

    public $js = [
        'jquery.cookie.js',
        'ecommerce.js',
        'infiniteListView.js',
        'favorites.js',
        'cart.js',
        'zoomer.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
