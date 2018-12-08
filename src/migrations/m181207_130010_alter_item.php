<?php

use yii\db\Migration;

/**
 * Class m181127_130000_alter_item
 */
class   m181207_130010_alter_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%ec_item}}", "weight_delivery", $this->float()
            ->notNull()
            ->defaultValue(0)
            ->comment('Weight for delivery'));

        $this->addColumn("{{%ec_order}}", "city_id", $this->integer()
            ->null()
            ->comment('City ID for delivery service'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%ec_item}}", "weight_delivery");
        $this->dropColumn("{{%ec_order}}", "city_id");

    }

}
