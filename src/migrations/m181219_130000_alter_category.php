<?php

use yii\db\Migration;

/**
 * Class m181219_130000_alter_category
 */
class m181219_130000_alter_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_category}}", "path", $this->string()->null()->comment('Title with full path'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_category}}", "path");
    }

}
