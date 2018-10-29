<?php

use yii\db\Migration;

/**
 * Class m181012_154618_init
 */
class m181025_130000_alter_params_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_item_param_value}}", "parent_item_id", $this->string()->null()->comment('Parent item id'));
        $this->createIndex('idx-ec_item_param_value-parent_item_id', "{{%ec_item_param_value}}", "parent_item_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_item_param_value}}", "parent_item_id");
    }

}
