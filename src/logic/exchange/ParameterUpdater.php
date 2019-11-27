<?php


namespace floor12\ecommerce\logic\exchange;


use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\entity\Product;
use Yii;
use yii\base\ErrorException;

class ParameterUpdater
{
    /**
     * @var int
     */
    protected $productVariaionId;
    /**
     * @var string
     */
    protected $parameterName;
    /**
     * @var string
     */
    protected $parameterValue;

    /**
     * @var Parameter;
     */
    protected $parameter;
    /**
     * @var ParameterValue
     */
    protected $parameterValueObject;

    /**
     * ParameterUpdater constructor.
     * @param int $productVariaionId
     * @param string $parameterName
     * @param string $parameterValue
     */
    public function __construct(int $productVariationId, string $parameterName, string $parameterValue)
    {
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
        $this->productVariaionId = $productVariationId;
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function update()
    {
        if (empty($this->parameter))
            $this->findOrCreateParameter();

        return $this->updateParameterValue();
    }

    /**
     * @return Product
     * @throws ErrorException
     */
    protected function findOrCreateParameter()
    {
        $this->parameter = Parameter::find()
            ->where(['title' => $this->parameterName])
            ->one();

        if (is_object($this->parameter))
            return $this->parameter;

        $this->parameter = new Parameter([
            'title' => $this->parameterName,
            'external_id' => $this->parameterUID,
            'sort' => Parameter::find()->max('sort') + 1

        ]);

        if (!$this->parameter->save())
            throw new ErrorException('Error stock saving: ' . print_r($this->parameter->getFirstErrors(), 1));

        return $this->parameter;
    }

    /**
     * @return bool
     */
    protected function updateParameterValue()
    {
        $this->parameterValueObject = ParameterValue::find()
            ->where([
                'value' => $this->parameterValue,
                'parameter_id' => $this->parameter->id
            ])->one();

        if (!is_object($this->parameterValueObject))
            $this->parameterValueObject = new ParameterValue([
                'value' => $this->parameterValue,
                'parameter_id' => $this->parameter->id,
                'sort' => ParameterValue::find()->where(['parameter_id' => $this->parameter->id])->max('sort') + 1

            ]);

        if (!$this->parameterValueObject->save())
            throw new ErrorException('ParameterValue saving error.');


        $link = Yii::$app
            ->db
            ->createCommand("SELECT count(*) FROM ec_parameter_value_product_variation WHERE 
                    parameter_value_id={$this->parameterValueObject->id} 
                    AND product_variation_id = {$this->productVariaionId}")
            ->queryScalar();
        if (!$link)
            Yii::$app->db->createCommand()->insert('ec_parameter_value_product_variation', [
                'parameter_value_id' => $this->parameterValueObject->id,
                'product_variation_id' => $this->productVariaionId
            ])->execute();

        return true;
    }


}