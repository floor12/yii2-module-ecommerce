<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 14/10/2018
 * Time: 08:59
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\Item;
use floor12\ecommerce\models\entity\Parameter;
use http\Exception\InvalidArgumentException;
use yii\base\ErrorException;
use yii\base\Model;

class ItemParamsForm extends Model
{
    public $category_params = [];
    public $params = [];
    public $params_values = [];
    public $id;

    private $_item;
    private $_option_item;
    private $_categories;


    /**
     * ItemParamsForm constructor.
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        if ($item->isNewRecord)
            throw new InvalidArgumentException('Item is new record.');

        $this->_item = $item;
        $this->id = $item->id;


        if ($this->_item->parent_id) {
            $this->_item = $item->parent;
            $this->_option_item = $item;
        }


        parent::__construct([]);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            ['params_values', 'each', 'rule' => ['string']]
        ];
    }

    private function addCategory(Category $category)
    {
        $this->_categories[] = $category;
        if ($category->parent)
            $this->addCategory($category->parent);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->_item->categories)
            foreach ($this->_item->categories as $category)
                $this->addCategory($category);

        if ($this->_categories)
            foreach ($this->_categories as $category) {
                if ($category->params)
                    foreach ($category->params as $parameter)
                        $this->category_params[$parameter->id] = $parameter;
            }

        $this->category_params += Parameter::find()
            ->root()
            ->indexBy('id')
            ->all();


        if ($this->category_params)
            foreach ($this->category_params as $key => $category_param) {
                $this->params[$key] = [
                    'label' => $category_param->title,
                    'unit' => $category_param->unit,
                    'type_id' => $category_param->type_id,
                    'value' => ItemParamValue::find()
                        ->where([
                            'param_id' => $category_param->id,
                            'item_id' => $this->_option_item ? $this->_option_item->id : $this->_item->id
                        ])
                        ->select('value')
                        ->scalar()
                ];
                $this->params_values[$key] = $this->params[$key]['value'];
            }
    }

    public function saveParams()
    {
        ItemParamValue::deleteAll(['item_id' => $this->_option_item ? $this->_option_item->id : $this->_item->id]);
        if ($this->params_values)
            foreach ($this->params_values as $param_id => $params_value) {
                if (empty($params_value))
                    continue;
                $paramModel = new ItemParamValue([
                    'item_id' => $this->_option_item ? $this->_option_item->id : $this->_item->id,
                    'parent_item_id' => $this->_item->id,
                    'param_id' => $param_id,
                    'value' => $params_value,
                    'unit' => $this->params[$param_id]['unit']
                ]);
                if (!$paramModel->save())
                    throw new ErrorException("Error while saving param: " . print_r($paramModel->errors, true));
            }
        return true;
    }
}