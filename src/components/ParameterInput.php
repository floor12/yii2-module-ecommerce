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
use yii\base\Widget;
use yii\helpers\Html;
use yii2mod\slider\IonSlider;

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

        return Html::tag('div', $this
            ->form
            ->field($this->filter, "param_values[{$this->parameter->id}]", ['addon' => ['prepend' => ['content' => $this->parameter->title]]])
            ->checkboxButtonGroup($this->_values, ['class' => 'btn-group-sm'])
            ->label(false), ['class' => 'f12-ecommerce-checkbox-block']);
    }

    /**
     * @return \kartik\form\ActiveField
     * @throws \yii\base\InvalidConfigException
     */
    private function renderSlider()
    {

        $this->_values['min'] = EcItemParamValue::find()
            ->param($this->parameter->id)
            ->min('value');

        $this->_values['max'] = EcItemParamValue::find()
            ->param($this->parameter->id)
            ->max('value');

        return Html::tag('div', $this
            ->form
            ->field($this->filter, "param_values[{$this->parameter->id}]")
            ->widget(IonSlider::class, [
                'pluginOptions' => [
                    'keyboard' => false,
                    'force_edges' => true,
                    'postfix' => ' ' . $this->parameter->unit,
                    'min' => $this->_values['min'] ?: 0,
                    'max' => $this->_values['max'] ?: 0,
                    'grid' => true,
                    'type' => 'double',
                    'step' => 1,
                ]
            ])
            ->label($this->parameter->title), ['class' => 'f12-ecommerce-slider-block']);

    }

}