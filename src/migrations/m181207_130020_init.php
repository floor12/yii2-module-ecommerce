<?php

use yii\db\Migration;

/**
 * Class m181207_130020_init
 */
class m181207_130020_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->compact = true;

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
