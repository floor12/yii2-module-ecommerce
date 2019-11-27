<?php

use yii\db\Migration;

/**
 * Class m191107_130020_init
 */
class m191107_130020_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->compact = true;
        $sql = "
        SET FOREIGN_KEY_CHECKS=0;
        DROP TABLE IF EXISTS ec_category;
        DROP TABLE IF EXISTS ec_city;
        DROP TABLE IF EXISTS ec_discount_group;
        DROP TABLE IF EXISTS ec_discount_group_product;
        DROP TABLE IF EXISTS ec_order;
        DROP TABLE IF EXISTS ec_order_item;
        DROP TABLE IF EXISTS ec_parameter;
        DROP TABLE IF EXISTS ec_parameter_category;
        DROP TABLE IF EXISTS ec_parameter_value;
        DROP TABLE IF EXISTS ec_parameter_value_product_variation;
        DROP TABLE IF EXISTS ec_payment;
        DROP TABLE IF EXISTS ec_product;
        DROP TABLE IF EXISTS ec_product_category;
        DROP TABLE IF EXISTS ec_product_variation;
        DROP TABLE IF EXISTS ec_stock;
        DROP TABLE IF EXISTS ec_stock_balance;
        SET FOREIGN_KEY_CHECKS=1;";
        Yii::$app->db->createCommand($sql)->execute();

        try {
            $this->execute(file_get_contents(Yii::getAlias("@vendor/floor12/yii2-module-ecommerce/src/migrations/ec_init.sql")), []);
        } catch (Exception $e) {
            echo "Error in init ecommerce module tables." . PHP_EOL;
        }


        try {
            $this->execute(file_get_contents(Yii::getAlias("@vendor/floor12/yii2-module-ecommerce/src/migrations/ec_city.sql")), []);
        } catch (Exception $e) {
            echo "Error import into database" . PHP_EOL;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

}
