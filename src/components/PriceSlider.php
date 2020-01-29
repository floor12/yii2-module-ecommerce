<?php


namespace floor12\ecommerce\components;


use ruskid\nouislider\sliders\TwoHandleSlider;
use yii\web\JsExpression;

class PriceSlider extends TwoHandleSlider
{
    /**
     * Sync container ids with slider
     */
    protected function registerUpdateEvent()
    {
        $this->events[self::NOUI_EVENT_UPDATE] = new JsExpression(
            "function( values, handle ) {
   
            if('$this->lowerValueContainerId'){
                document.getElementById('$this->lowerValueContainerId').value = values[0];
            }

            if('$this->upperValueContainerId'){
                document.getElementById('$this->upperValueContainerId').value = values[1];
            }  
                    }");

        $this->events[self::NOUI_EVENT_END] = new JsExpression(
            "function( values, handle ) {
 
            submitForm($('#f12-eccomerce-product-filter'));
        }");
    }
}
