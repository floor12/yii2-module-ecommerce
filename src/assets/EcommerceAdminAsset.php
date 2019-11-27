<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 18:07
 */

namespace floor12\ecommerce\assets;

use yii\web\AssetBundle;

class EcommerceAdminAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-module-ecommerce/src/assets';

    public $js = [
        'ecommerce-admin.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'floor12\notification\NotificationAsset'
    ];
}
