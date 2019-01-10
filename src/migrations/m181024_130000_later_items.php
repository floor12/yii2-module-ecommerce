<?php

use yii\db\Migration;

/**
 * Class m181012_154618_init
 */
class m181024_130000_later_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_item}}", "parent_id", $this->integer()->defaultValue(0)->null()->comment('Parent item'));
        $this->createIndex('idx-ecec_item_order-parent_id', "{{%ec_item}}", "parent_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_item}}", "parent_id");
    }

}
