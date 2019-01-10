<?php

use yii\db\Migration;

/**
 * Class m181127_130000_alter_item
 */
class m181127_130000_alter_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_item}}", "article", $this->string()->null()->comment('Item article'));
        $this->createIndex('idx-ec_item-article', "{{%ec_item}}", "article");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_item}}", "article");

    }

}
