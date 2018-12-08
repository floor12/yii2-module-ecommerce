<?php

use yii\db\Migration;

/**
 * Class m181208_190721_alter_item
 */
class m181208_190721_alter_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn('{{%ec_order}}', 'delivery_cost',
            $this->float()->defaultValue(0)->notNull()->comment('Delivery cost'));

        $this->addColumn('{{%ec_order}}', 'items_cost',
            $this->float()->defaultValue(0)->notNull()->comment('All items cost'));

        $this->addColumn('{{%ec_order}}', 'items_weight',
            $this->float()->defaultValue(0)->notNull()->comment('All items weight'));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ec_order}}', 'delivery_cost');
        $this->dropColumn('{{%ec_order}}', 'items_cost');
        $this->dropColumn('{{%ec_order}}', 'items_weight');
    }
}
