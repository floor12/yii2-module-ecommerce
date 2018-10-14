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
