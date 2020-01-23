<?php

use yii\db\Migration;

/**
 * Class m200123_090942_alter_param_value_fk
 */
class m200123_090942_alter_param_value_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('ec_parameter_value_product_variation_ec_parameter_value_id_fk', 'ec_parameter_value_product_variation');
        $this->addForeignKey('ec_parameter_value_product_variation_ec_parameter_value_id_fk', 'ec_parameter_value_product_variation', 'parameter_value_id', 'ec_parameter_value', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200123_090942_alter_param_value_fk cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200123_090942_alter_param_value_fk cannot be reverted.\n";

        return false;
    }
    */
}
