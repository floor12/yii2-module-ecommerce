<?php

use yii\db\Migration;

/**
 * Class m181127_130000_alter_item
 */
class m181207_130020_create_city extends Migration
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


        $this->createTable('{{%ec_city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('City name'),
            'fullname' => $this->string()->notNull()->comment('City name with region'),
        ], $tableOptions);

        $this->compact = true;


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
        $this->dropTable('{{%ec_city}}');

    }

}
