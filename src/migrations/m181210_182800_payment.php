<?php

use floor12\ecommerce\models\enum\PaymentType;
use yii\db\Migration;

/**
 * Class m181210_182800_payment
 */
class m181210_182800_payment extends Migration
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

        $this->createTable('{{%ec_payment}}', [
            'id' => $this->primaryKey(),
            'created' => $this->integer()->notNull()->comment('Creation timestamp'),
            'updated' => $this->integer()->notNull()->comment('Update timestamp'),
            'payed' => $this->integer()->null()->comment('Payed timestamp'),
            'order_id' => $this->integer()->notNull()->comment('Order id'),
            'status' => $this->integer()->notNull()->comment('Payment status'),
            'type' => $this->integer()->notNull()->comment('Payment type'),
            'external_id' => $this->integer()->null()->comment('External payment system id'),
            'sum' => $this->float()->notNull()->comment('Sum'),
            'comment' => $this->text()->null()->comment('Payment comment')
        ], $tableOptions);

        $this->createIndex('idx-ec_payment-order_id', '{{%ec_payment}}', 'order_id');
        $this->createIndex('idx-ec_payment-status', '{{%ec_payment}}', 'status');
        $this->createIndex('idx-ec_payment-type', '{{%ec_payment}}', 'type');

        $this->addForeignKey('fk-ec_payment-order', '{{%ec_payment}}', 'order_id', '{{%ec_order}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->addColumn('{{%ec_order}}', 'payment_type_id', $this
            ->integer()
            ->null()
            ->defaultValue(PaymentType::RECEIVING)
        );

        $this->createIndex('idx-ec_order-type', '{{%ec_order}}', 'payment_type_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ec_order}}', 'payment_type_id');
        $this->dropForeignKey('fk-ec_payment-order', '{{%ec_payment}}');
        $this->dropTable('{{%ec_payment}}');
    }
}
