<?php

use yii\db\Migration;

/**
 * Class m200123_090942_alter_param_value_fk
 */
class m200505_120000_add_discount_rels_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ec_order_item}}', 'discount_group_id', $this->integer()->null());
        $this->addColumn('{{%ec_order_item}}', 'full_price', $this->float()->null());
        $this->addColumn('{{%ec_order_item}}', 'discount_percent', $this->integer()->notNull()->defaultValue(0));

        $this->createIndex('ec_order_item-group_id', '{{%ec_order_item}}', 'discount_group_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ec_order_item}}', 'discount_group_id');
        $this->dropColumn('{{%ec_order_item}}', 'full_price');
        $this->dropColumn('{{%ec_order_item}}', 'discount_percent');
    }

}
