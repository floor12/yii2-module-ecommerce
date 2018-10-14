<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 14/10/2018
 * Time: 08:59
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\EcItem;
use floor12\ecommerce\models\EcItemParamValue;
use http\Exception\InvalidArgumentException;
use yii\base\Model;

class ItemParamsForm extends Model
{
    public $category_params = [];
    public $params = [];
    public $params_values = [];

    private $_item;


    /**
     * ItemParamsForm constructor.
     * @param EcItem $item
     */
    public function __construct(EcItem $item)
    {
        if ($item->isNewRecord)
            throw new InvalidArgumentException('Item is new record.');

        $this->_item = $item;

        parent::__construct([]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->_item->categories)
            foreach ($this->_item->categories as $category) {
                foreach ($category->params as $parameter)
                    $this->category_params[] = $parameter;
            }

        if ($this->category_params)
            foreach ($this->category_params as $key => $category_param) {
                $this->params[$key] = [
                    'label' => $category_param->title,
                    'unit' => $category_param->unit,
                    'type_id' => $category_param->type_id,
                    'value' => EcItemParamValue::findOne([
                        'param_id' => $category_param->id,
                        'item_id' => $this->_item->id
                    ])
                ];
            }
    }
}