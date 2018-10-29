<?php

use yii\db\Migration;

/**
 * Class m181027_130000_alter_order
 */
class m181027_130000_alter_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_order}}", "delivery_type_id", $this->integer()->notNull()->comment('Delivery type'));
        $this->addColumn("{{%ec_order}}", "fullname", $this->string()->null()->comment('Fullname'));
        $this->addColumn("{{%ec_order}}", "phone", $this->string()->null()->comment('Phone'));
        $this->addColumn("{{%ec_order}}", "email", $this->string()->null()->comment('Email'));
        $this->addColumn("{{%ec_order}}", "address", $this->text()->null()->comment('Address'));
        $this->addColumn("{{%ec_order}}", "comment", $this->text()->null()->comment('Client comment'));
        $this->addColumn("{{%ec_order}}", "comment_admin", $this->text()->null()->comment('Admin comment'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_order}}", "delivery_type_id");
        $this->dropColumn("{{%ec_order}}", "fullname");
        $this->dropColumn("{{%ec_order}}", "email");
        $this->dropColumn("{{%ec_order}}", "phone");
        $this->dropColumn("{{%ec_order}}", "address");
        $this->dropColumn("{{%ec_order}}", "comment");
        $this->dropColumn("{{%ec_order}}", "comment_admin");
    }

}
