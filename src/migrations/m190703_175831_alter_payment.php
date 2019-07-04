<?php

use yii\db\Migration;

/**
 * Class m190703_175831_alter_payment
 */
class m190703_175831_alter_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('{{%ec_payment}}', 'external_id', $this->string(255)->null());
        $this->addColumn('{{%ec_payment}}', 'form_url', $this->string(512)->null());
        $this->addColumn('{{%ec_payment}}', 'external_status', $this->integer(1)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%ec_payment}}', 'external_id', $this->integer(11)->null());
        $this->dropColumn('{{%ec_payment}}', 'form_url');
        $this->dropColumn('{{%ec_payment}}', 'external_status');
    }

}
