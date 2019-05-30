<?php

namespace floor12\ecommerce;

use floor12\ecommerce\assets\EcommerceTagAsset;
use Yii;
use yii\base\ErrorException;

/**
 * pages module definition class
 * @property  string $editRole
 */
class Module extends \yii\base\Module
{

    public $layout = '@app/views/layouts/main';

    public $userModel = 'app\models\User';

    public $exportPath = '@runtime/export';

    public $importPath = '@runtime/import';

    public $registerGoogleTagEvents = false;

    public $useAjaxAddToCartWidget = false;
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

        if (!file_exists(Yii::getAlias($this->exportPath)))
            @mkdir(Yii::getAlias($this->exportPath));

        if (!file_exists(Yii::getAlias($this->exportPath)))
            throw new ErrorException('Unable to create export path.');

        if (!file_exists(Yii::getAlias($this->importPath)))
            @mkdir(Yii::getAlias($this->importPath));

        if (!file_exists(Yii::getAlias($this->importPath)))
            throw new ErrorException('Unable to create import path.');
        

        if (Yii::$app->controllerNamespace != 'app\commands' && Yii::$app->controllerNamespace != 'console\controllers')
            if ($this->registerGoogleTagEvents) {
                EcommerceTagAsset::register(Yii::$app->getView());
                Yii::$app->getView()->registerJs('var registerGoogleTagEvents = true;');
            } else {
                Yii::$app->getView()->registerJs('var registerGoogleTagEvents = false;');
            }
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

    public function adminMode()
    {
        if ($this->editRole == '@')
            return !\Yii::$app->user->isGuest;
        else
            return \Yii::$app->user->can($this->editRole);
    }
}
