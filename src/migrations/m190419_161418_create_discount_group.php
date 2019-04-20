<?php

use floor12\ecommerce\models\Item;
use yii\db\Migration;

/**
 * Class m190419_161418_create_discount_group
 */
class m190419_161418_create_discount_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // New prices fields in Item table
        $this->addColumn(Item::tableName(), 'price2', $this->double()->notNull()->defaultValue(0)->comment('Second price'));
        $this->addColumn(Item::tableName(), 'price3', $this->double()->notNull()->defaultValue(0)->comment('Third price'));

        // New table to store discounts
        $this->createTable('{{%ec_discount_group}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull()->comment('Created at'),
            'created_by' => $this->integer()->notNull()->comment('Created by'),
            'updated_at' => $this->integer()->notNull()->comment('Updated at'),
            'updated_by' => $this->integer()->notNull()->comment('Updated by'),
            'title' => $this->string()->notNull()->comment('Group title'),
            'description' => $this->string()->notNull()->comment('Discount description'),
            'status' => $this->integer()->notNull()->comment('Status'),
            'discount_price_id' => $this->integer()->null()->comment('Discount item price id'),
            'discount_percent' => $this->integer()->null()->comment('Discount in percents'),
            'item_quantity' => $this->integer()->null()->comment('Quantity of items of this group'),
        ], $tableOptions);

        $this->createIndex('idx-ec_discount_group-status', '{{%ec_discount_group}}', 'status');
        $this->createIndex('idx-ec_discount_group-item_quantity', '{{%ec_discount_group}}', 'item_quantity');

        // Link table
        $this->createTable('{{%ec_discount_group_item}}', [
            'discount_group_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-ec_discount_group_item', '{{%ec_discount_group_item}}', ['discount_group_id', 'item_id']);

        $this->addForeignKey('fk-ec_discount_group_item', '{{%ec_discount_group_item}}', 'item_id', Item::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ec_discount_group_group', '{{%ec_discount_group_item}}', 'discount_group_id', '{{%ec_discount_group}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ec_discount_group_item}}');
        $this->dropTable('{{%ec_discount_group}}');
        $this->dropColumn(Item::tableName(), 'price2');
        $this->dropColumn(Item::tableName(), 'price3');

    }

}
