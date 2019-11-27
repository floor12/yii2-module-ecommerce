<?php


namespace floor12\ecommerce\logic\discounts;


use floor12\editmodal\LogicWithIdentityInterface;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

class DiscountGroupUpdate implements LogicWithIdentityInterface
{
    protected $model;
    protected $data;
    protected $identity;

    /**
     * DiscountPriceUpdate constructor.
     * @param ActiveRecordInterface $model
     * @param array $data
     * @param IdentityInterface $identity
     */
    public function __construct(ActiveRecordInterface $model, array $data, IdentityInterface $identity)
    {
        $this->model = $model;
        $this->data = $data;
        $this->identity = $identity;

        $this->model->setAttribute('updated_at', time());
        $this->model->setAttribute('updated_by', $this->identity->getId());

        if ($this->model->getIsNewRecord()) {
            $this->model->setAttribute('created_at', time());
            $this->model->setAttribute('created_by', $this->identity->getId());
        }
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->model->load($this->data);
        return $this->model->save();
    }

}