<?php


namespace floor12\ecommerce\models\forms;


use yii\base\Model;

class ProductSelectorForm extends Model
{
    public $parameterValueIds = [];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['parameterValueIds', 'safe']
        ];
    }
}