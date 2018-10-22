<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 21/10/2018
 * Time: 19:16
 */

namespace floor12\ecommerce\components;


use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\EcItemParam;
use floor12\ecommerce\models\EcItemParamValue;
use floor12\ecommerce\models\enum\ParamType;
use floor12\ecommerce\models\filters\ItemFrontendFilter;
use kartik\slider\Slider;
use yii\base\Widget;

/**
 * Class ParameterInput
 * @package floor12\ecommerce\components
 * @property EcItemParam $parameter
 * @property EcCategory $category
 * @property \kartik\form\ActiveForm $form
 * @property ItemFrontendFilter $filter
 */
class ParameterInput extends Widget
{
    public $parameter;
    public $category;
    public $form;
    public $filter;

    private $_values = [];

    /**
     * @return \kartik\form\ActiveField
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        if ($this->parameter->type_id == ParamType::CHECKBOX)
            return $this->renderBtnGroup();

        if ($this->parameter->type_id == ParamType::SLIDER)
            return $this->renderSlider();


    }

    /**
     * @return \kartik\form\ActiveField
     * @throws \yii\base\InvalidConfigException
     */
    private function renderBtnGroup()
    {
        $this->_values = EcItemParamValue::find()
            ->select('value')
            ->indexBy('value')
            ->param($this->parameter->id)
            ->distinct()
            ->column();

        return $this
            ->form
            ->field($this->filter, "param_values[{$this->parameter->id}]")
            ->checkboxButtonGroup($this->_values)
            ->label($this->parameter->title);
    }

    /**
     * @return \kartik\form\ActiveField
     * @throws \yii\base\InvalidConfigException
     */
    private function renderSlider()
    {

        $this->_values = EcItemParamValue::find()
            ->select('value')
            ->indexBy('value')
            ->param($this->parameter->id)
            ->distinct()
            ->column();

        return $this
            ->form
            ->field($this->filter, "param_values[{$this->parameter->id}]")
            ->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1
                ]
            ])
            ->label($this->parameter->title);

    }

}