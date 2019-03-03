<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/10/2018
 * Time: 20:27
 */

namespace floor12\ecommerce\components;


use floor12\ecommerce\models\ItemParam;
use floor12\ecommerce\models\ItemParamValue;
use yii\base\Widget;

class AddToCartWidgetAjax extends Widget
{
    public $item;

    private $params;
    private $_showProceedBtn = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $paramsUnqueIds = ItemParamValue::find()
            ->select('param_id')
            ->distinct()
            ->where(['parent_item_id' => $this->item->id])
            ->column();

        foreach ($paramsUnqueIds as $param_id) {
            $this->params[$param_id]['title'] = ItemParam::find()
                ->select('title')
                ->where(['id' => $param_id])
                ->scalar();

            $this->params[$param_id]['values'] = ItemParamValue::find()
                ->select('value')
                ->indexBy('value')
                ->orderBy('value')
                ->distinct()
                ->where([
                    'parent_item_id' => $this->item->id,
                    'param_id' => $param_id
                ])->column();

            if (sizeof($this->params[$param_id]['values']) <= 1)
                unset($this->params[$param_id]);

        }

    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        return $this->render('addToCartWidgetAjax', [
            'item_id' => $this->item->id,
            'params' => $this->params
        ]);
    }
}