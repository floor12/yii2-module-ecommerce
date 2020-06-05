<?php

use yii\db\Migration;

/**
 * Class m200604_121912_add_color_to_parameter_value
 */
class m200604_121912_add_color_to_parameter_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ec_parameter_value', 'color_hex', $this->string(7)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('ec_parameter_value', 'color_hex');
    }

}
