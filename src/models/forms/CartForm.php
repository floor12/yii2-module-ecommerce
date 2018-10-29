<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 27/10/2018
 * Time: 11:08
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\Item;
use Yii;
use yii\base\Model;

class CartForm extends Model
{
    public $total = 0;
    public $rows = [];

    public function init()
    {
        foreach ($_COOKIE as $name => $quantity) {
            if (preg_match('/cart-(\d+)/', $name, $matches)) {
                $item = Item::findOne($matches[1]);
                if (!$item)
                    continue;

                $sum = $quantity * $item->price_current;
                $this->total = $this->total + $sum;

                $this->rows[$item->id] = [
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => Yii::$app->formatter->asCurrency($item->price_current, Yii::$app->getModule('shop')->currency),
                    'sum' => Yii::$app->formatter->asCurrency($sum, Yii::$app->getModule('shop')->currency),
                ];
            }
        }

        ksort($this->rows);
        $this->total = Yii::$app->formatter->asCurrency($this->total, Yii::$app->getModule('shop')->currency);
    }


    /**
     *  epmty cart
     */
    public function  empty()
    {
        foreach ($_COOKIE as $name => $quantity) {
            if (preg_match('/cart-(\d+)/', $name, $matches))
                setcookie($matches[0], '', time() - 3600, '/');
        }
    }
}