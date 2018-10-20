<?php

use yii\db\Migration;

/**
 * Class m181012_154618_init
 */
class m181018_130000_external_ids extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_item}}", "external_id", $this->string()->null()->comment('Extermnl indificator'));
        $this->addColumn("{{%ec_category}}", "external_id", $this->string()->null()->comment('Extermnl indificator'));
        $this->addColumn("{{%ec_item_param}}", "external_id", $this->string()->null()->comment('Extermnl indificator'));
        $this->addColumn("{{%ec_order}}", "external_id", $this->string()->null()->comment('Extermnl indificator'));

        $this->addColumn("{{%ec_item_param}}", "hide", $this->integer()->notNull()->defaultValue(0)->comment('Hide on site'));

        $this->createIndex('idx-ec_item-external_id', "{{%ec_item}}", "external_id");
        $this->createIndex('idx-ec_category-external_id', "{{%ec_category}}", "external_id");
        $this->createIndex('idx-ec_item_param-external_id', "{{%ec_item_param}}", "external_id");
        $this->createIndex('idx-ec_item_param-hide_id', "{{%ec_item_param}}", "hide");
        $this->createIndex('idx-ec_order-external_id', "{{%ec_order}}", "external_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_order}}", "external_id");
        $this->dropColumn("{{%ec_item_param}}", "external_id");
        $this->dropColumn("{{%ec_item_param}}", "hide");
        $this->dropColumn("{{%ec_category}}", "external_id");
        $this->dropColumn("{{%ec_item}}", "external_id");
    }

}
