<?php

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\Item;

class ParamProcessor
{
    /**
     * @var Item
     */
    protected $model;

    protected $paramsStringsArray = [];

    /**
     * ParamProcessor constructor.
     * @param Item $model
     */
    public function __construct(Item $model)
    {
        $this->model = $model;
    }

    /**
     * @return string|void
     */
    public function getParamsInString()
    {
        if (!$this->model->itemParamValues)
            return;

        foreach ($this->model->itemParamValues as $paramValue)
            $this->paramsStringsArray[] = trim("{$paramValue->param->title}: {$paramValue->value} {$paramValue->unit}");

        return implode(', ', $this->paramsStringsArray);
    }
}