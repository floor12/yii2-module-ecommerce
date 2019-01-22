<?php

namespace floor12\ecommerce;

use Yii;

/**
 * pages module definition class
 * @property  string $editRole
 */
class Module extends \yii\base\Module
{

    public $layout = '@app/views/layouts/main';

    public $userModel = 'app\models\User';
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'floor12\ecommerce\controllers';

    /**
     * Те роли в системе, которым разрешено управление магазином
     * @var array
     */
    public $editRole = '@';

    /** Валюта магазига
     * @var string
     */
    public $currency = 'RUB';

    /** Знак валюты
     * @var string
     */
    public $currencyLabel = '₽';

    /** Кол-во товаров на странице
     * @var integer
     */
    public $itemPerPage = 6;

    /** Default item weight for delivery in kg.
     * @var float
     */
    public $defaultDeliveryWeight = 0.4;

    /** Default item width for delivery in cm.
     * @var float
     */
    public $defaultDeliveryWidth = 30;

    /** Default item width for delivery in cm.
     * @var float
     */
    public $defaultDeliveryHeight = 40;

    /** Default item width for delivery in cm.
     * @var float
     */
    public $defaultDeliveryDepth = 5;

    /** Sdek sender city id
     * @var int
     */
    public $sdekCityFromId = 173;

    public $paymentDescription = 'Payment in online store.';

    /** Sdek tariff
     * @var int
     */
    public $sdekDeliveryTariff = 1;

    public $payment_types = [];

    public $payment_params = [];

    /** View paths
     * @var string
     */
    public $viewIndex = '@vendor/floor12/yii2-module-ecommerce/src/views/category/index';
    public $viewIndexListItem = '@vendor/floor12/yii2-module-ecommerce/src/views/category/_index';
    public $viewItem = '@vendor/floor12/yii2-module-ecommerce/src/views/category/item';


    /**
     * @var array Enable delivery types
     */
    public $deliveryTypes = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslations();

        parent::init();
    }

    public function adminMode()
    {
        if ($this->editRole == '@')
            return !\Yii::$app->user->isGuest;
        else
            return \Yii::$app->user->can($this->editRole);
    }


    public function registerTranslations()
    {
        Yii::$app->i18n->translations['app.f12.ecommerce'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vendor/floor12/yii2-module-ecommerce/src/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                'app.f12.ecommerce' => 'ecommerce.php',
            ],
        ];
    }
}
