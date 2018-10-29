<?php
/**
 * Created by PhpStorm.
 * User: evgenygoryaev
 * Date: 14/08/2017
 * Time: 15:23
 */

namespace floor12\ecommerce\components;

use app\components\FontAwesome;
use yii\base\Widget;
use yii\helpers\Html;

class FavWidget extends Widget
{
    public $id;

    public function run()
    {
        return Html::tag('a', FontAwesome::icon('star', 's'), [
            'class' => isset($_COOKIE["fav-{$this->id}"]) ? 'fav fav-active' : 'fav',
            'title' => isset($_COOKIE["fav-{$this->id}"]) ? 'Удалить из избранного' : 'Добавить в избранное',
            'data-id' => $this->id
        ]);
    }
}