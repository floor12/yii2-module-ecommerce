<?php


namespace floor12\ecommerce\logic\parameters;


use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use yii\base\ErrorException;

class ParameterValueFinder
{
    /**
     * @var string
     */
    protected $value;
    /**
     * @var Parameter
     */
    protected $parameter;
    /**
     * @var ParameterValue
     */
    protected $parameterValue;

    /**
     * ParameterValueFinder constructor.
     * @param string $value
     * @param Parameter $parameter
     */
    public function __construct(string $value, Parameter $parameter)
    {
        $this->value = $value;
        $this->parameter = $parameter;
    }

    /**
     * @throws ErrorException
     */
    protected function findValue()
    {
        if (is_object($this->parameterValue))
            return $this->parameterValue;

        $this->parameterValue = ParameterValue::find()
            ->where([
                'value' => $this->value,
                'parameter_id' => $this->parameter->id
            ])->one();

        if (!is_object($this->parameterValue))
            $this->parameterValue = new ParameterValue([
                'value' => $this->value,
                'parameter_id' => $this->parameter->id
            ]);

        if (!$this->parameterValue->save())
            throw new ErrorException('ParameterValue saving error.');
    }

    /**
     * @return int
     * @throws ErrorException
     */
    public function getValueId()
    {
        $this->findValue();
        return $this->parameterValue->id;
    }
}