<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25/10/2018
 * Time: 11:14
 */

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\Item;
use floor12\editmodal\LogicInterface;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Class ItemUpdate
 * @package floor12\ecommerce\logic
 * @property Item $_model
 */
class ItemUpdate implements LogicInterface
{

    private $_model;
    private $_data;

    /**
     * LogicInterface constructor.
     * @param $model
     * @param array $data
     * @param Item $identity
     */
    public function __construct(ActiveRecordInterface $model, array $data, IdentityInterface $identity)
    {
        $this->_model = $model;
        $this->_data = $data;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->_model->load($this->_data);

        $this->_model->on(ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
            if ($event->sender->options)
                foreach ($event->sender->options as $option) {
                    $option->status = $event->sender->status;
                    $option->save();
                }
        });

        return $this->_model->save();
    }
}