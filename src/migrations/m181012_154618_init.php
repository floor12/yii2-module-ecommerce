<?php

use yii\db\Migration;

/**
 * Class m181012_154618_init
 */
class m181012_154618_init extends Migration
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

        // Category
        $this->createTable('{{%ec_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Category title'),
            'parent_id' => $this->integer()->null()->comment('Parent category'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Category status')
        ], $tableOptions);

        $this->createIndex('idx-ec_category-status', '{{%ec_category}}', 'status');

        //items
        $this->createTable('{{%ec_item}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Item title'),
            'subtitle' => $this->string()->null()->comment('Item subtitle'),
            'description' => $this->string()->null()->comment('Item description'),
            'seo_description' => $this->text()->null()->comment('Description META'),
            'seo_title' => $this->string()->null()->comment('Page title'),
            'price' => $this->float()->null()->comment('Price'),
            'price_discount' => $this->float()->null()->comment('Discount price'),
            'available' => $this->integer()->defaultValue(0)->null()->comment('Available quantity'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Item status')
        ], $tableOptions);

        $this->createIndex('idx-ec_item-status', '{{%ec_item}}', 'status');
        $this->createIndex('idx-ec_item-price', '{{%ec_item}}', 'price');
        $this->createIndex('idx-ec_item-price_discount', '{{%ec_item}}', 'price_discount');
        $this->createIndex('idx-ec_item-availible', '{{%ec_item}}', 'availible');

        //item-category link
        $this->createTable('{{%ec_item_category}}', [
            'item_id' => $this->integer()->notNull()->comment('Item link'),
            'category_id' => $this->integer()->notNull()->comment('Category link'),
        ], $tableOptions);

        $this->createIndex('idx-ec_item_category-status', '{{%ec_item_category}}', ['item_id', 'category_id']);
        $this->addForeignKey('fk-ec_item_category-item', '{{%ec_item_category}}', 'item_id', '{{%ec_item}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ec_item_category-category', '{{%ec_item_category}}', 'category_id', '{{%ec_category}}', 'id', 'CASCADE', 'CASCADE');

        //order
        $this->createTable('{{%ec_order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null()->comment('Buyer indificator'),
            'created' => $this->integer()->notNull()->comment('Created'),
            'updated' => $this->integer()->notNull()->comment('Updated'),
            'delivered' => $this->integer()->null()->comment('Delivered'),
            'total' => $this->float()->notNull()->defaultValue(0)->comment('Total cost'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Order status'),
            'delivery_status' => $this->integer()->notNull()->defaultValue(0)->comment('Delivery status'),
        ], $tableOptions);

        $this->createIndex('idx-ec_order-status', '{{%ec_order}}', 'status');
        $this->createIndex('idx-ec_order-user_id', '{{%ec_order}}', 'user_id');
        $this->createIndex('idx-ec_order-delivery_status', '{{%ec_order}}', 'delivery_status');
        $this->createIndex('idx-ec_order-created', '{{%ec_order}}', 'created');
        $this->createIndex('idx-ec_order-updated', '{{%ec_order}}', 'updated');

        //order items
        $this->createTable('{{%ec_order_item}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null()->comment('Buyer indificator'),
            'item_id' => $this->integer()->notNull()->comment('Item identificator'),
            'created' => $this->integer()->notNull()->comment('Created'),
            'order_id' => $this->integer()->notNull()->comment('Order identificator'),
            'quantity' => $this->integer()->notNull()->defaultValue(1)->comment('Order identificator'),
            'price' => $this->double()->notNull()->defaultValue(0)->comment('Item price'),
            'sum' => $this->double()->notNull()->defaultValue(0)->comment('Sum'),
            'order_status' => $this->integer()->notNull()->defaultValue(0)->comment('Order status'),
        ], $tableOptions);

        $this->createIndex('idx-ec_order_item-created', '{{%ec_order_item}}', 'created');
        $this->createIndex('idx-ec_order_item-user_id', '{{%ec_order_item}}', 'user_id');
        $this->createIndex('idx-ec_order_item-item_id', '{{%ec_order_item}}', 'item_id');
        $this->createIndex('idx-ec_order_item-order_id', '{{%ec_order_item}}', 'order_id');
        $this->createIndex('idx-ec_order_item-order_status', '{{%ec_order_item}}', 'order_status');

        $this->addForeignKey('fk-ec_order_item-order', '{{%ec_order_item}}', 'order_id', '{{%ec_order}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ec_order_item-item_id', '{{%ec_order_item}}', 'item_id', '{{%ec_item}}', 'id', 'RESTRICT', 'RESTRICT');

        //items params
        $this->createTable('{{%ec_item_param}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Parameter title'),
            'unit' => $this->string()->null()->comment('Parameter unit of measure'),
            'type_id' => $this->integer()->notNull()->defaultValue(0)->comment('Parameter type'),
        ], $tableOptions);


        $this->createIndex('idx-ec_item_param-type_id', '{{%ec_item_param}}', 'type_id');


        //param-category
        $this->createTable('{{%ec_param_category}}', [
            'param_id' => $this->integer()->notNull()->comment('Param link'),
            'category_id' => $this->integer()->notNull()->comment('Category link'),
        ], $tableOptions);

        $this->createIndex('idx-ec_param_category-status', '{{%ec_param_category}}', ['param_id', 'category_id']);
        $this->addForeignKey('fk-ec_param_category-param', '{{%ec_param_category}}', 'param_id', '{{%ec_item_param}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ec_param_category-category', '{{%ec_param_category}}', 'category_id', '{{%ec_category}}', 'id', 'CASCADE', 'CASCADE');


        //item param values
        $this->createTable('{{%ec_item_param_value}}', [
            'id' => $this->primaryKey(),
            'value' => $this->string()->notNull()->comment('Parameter value'),
            'unit' => $this->string()->null()->comment('Parameter unit of measure'),
            'param_id' => $this->integer()->notNull()->comment('Parameter id'),
            'item_id' => $this->integer()->notNull()->comment('Item id'),
        ], $tableOptions);

        $this->createIndex('idx-ec_item_param_value-param_id', '{{%ec_item_param_value}}', 'param_id');
        $this->createIndex('idx-ec_item_param_value-item_id', '{{%ec_item_param_value}}', 'item_id');
        $this->createIndex('idx-ec_item_param_value-value', '{{%ec_item_param_value}}', 'value');

        $this->addForeignKey('fk-ec_item_param_value-param_id', '{{%ec_item_param_value}}', 'param_id', '{{%ec_item_param}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ec_item_param_value-item_id', '{{%ec_item_param_value}}', 'item_id', '{{%ec_item}}', 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ec_item_param_value}}');
        $this->dropTable('{{%ec_item_param}}');
        $this->dropTable('{{%ec_order_item}}');
        $this->dropTable('{{%ec_item_category}}');
        $this->dropTable('{{%ec_order}}');
        $this->dropTable('{{%ec_category}}');
    }

}
