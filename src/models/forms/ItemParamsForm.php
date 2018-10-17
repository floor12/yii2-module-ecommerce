<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 14/10/2018
 * Time: 08:59
 */

namespace floor12\ecommerce\models\forms;


use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\EcItem;
use floor12\ecommerce\models\EcItemParamValue;
use http\Exception\InvalidArgumentException;
use yii\base\ErrorException;
use yii\base\Model;

class ItemParamsForm extends Model
{
    public $category_params = [];
    public $params = [];
    public $params_values = [];

    private $_item;
    private $_categories;


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
     * @return array
     */
    public function rules()
    {
        return [
            ['params_values', 'each', 'rule' => ['string']]
        ];
    }

    private function addCategory(EcCategory $category)
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


        if ($this->category_params)
            foreach ($this->category_params as $key => $category_param) {
                $this->params[$key] = [
                    'label' => $category_param->title,
                    'unit' => $category_param->unit,
                    'type_id' => $category_param->type_id,
                    'value' => EcItemParamValue::find()
                        ->where([
                            'param_id' => $category_param->id,
                            'item_id' => $this->_item->id
                        ])
                        ->select('value')
                        ->scalar()
                ];
                $this->params_values[$key] = $this->params[$key]['value'];
            }
    }

    public function saveParams()
    {
        EcItemParamValue::deleteAll(['item_id' => $this->_item->id]);
        if ($this->params_values)
            foreach ($this->params_values as $param_id => $params_value) {
                if (empty($params_value))
                    continue;
                $paramModel = new EcItemParamValue([
                    'item_id' => $this->_item->id,
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