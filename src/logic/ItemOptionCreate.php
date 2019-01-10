<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 24/10/2018
 * Time: 13:50
 */

namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\Item;
use yii\base\ErrorException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

class ItemOptionCreate
{

    private $_parent_model;
    private $_new_model;

    public function __construct(int $parent_id, IdentityInterface $identity)
    {
        $this->_parent_model = Item::findOne($parent_id);
        if (!$this->_parent_model)
            throw new NotFoundHttpException('Parent item is not found');

        $this->_new_model = new Item([
            'parent_id' => $this->_parent_model->id,
            'parent_id' => $this->_parent_model->id,
            'title' => $this->_parent_model->title,
            'article' => $this->_parent_model->article,
            'price' => $this->_parent_model->price,
            'price_discount' => $this->_parent_model->price_discount,
            'category_ids' => $this->_parent_model->category_ids,
            'available' => 0
        ]);
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if (!$this->_new_model->save())
            throw new ErrorException('Item option saving error: ' . print_r($this->_new_model->errors, true));

        return $this->_new_model;
    }
}