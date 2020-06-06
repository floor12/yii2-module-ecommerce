<?php
/**
 * Created by PhpStorm.
 * User: evgenygoryaev
 * Date: 14/08/2017
 * Time: 15:23
 */

namespace floor12\ecommerce\components;

use floor12\ecommerce\assets\IconHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class FavWidget extends Widget
{
    public $id;

    public function run()
    {
        return Html::button(IconHelper::STAR_FILLED . \floor12\editmodal\IconHelper::CLOSE, [
            'class' => isset($_COOKIE["fav-{$this->id}"]) ? 'fav fav-active' : 'fav',
            'title' => Yii::t('app.f12.ecommerce', isset($_COOKIE["fav-{$this->id}"]) ? 'Remove from favorites' : 'Add to favorites'),
            'data-id' => $this->id
        ]);
    }
}
