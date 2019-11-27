<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:40
 */

namespace floor12\ecommerce\tests;

use Yii;
use yii\console\Application;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{


    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::mockApplication();
        self::setApp();
    }


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    protected static function setApp()
    {
        $db = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=3336;dbname=database',
            'username' => 'yii2',
            'password' => 'database',
            'charset' => 'utf8',
        ];

        $shop = [
            'class' => 'floor12\ecommerce\Module',
            'exportPath' => '@app/_output/export',
            'importPath' => '@app/_output/import'
        ];

        Yii::$app->setModule('shop', $shop);
        Yii::$app->set('db', $db);


        try {
            Yii::$app->db->createCommand(file_get_contents(Yii::getAlias("@app/../src/migrations/ec_init.sql")))->execute();
        } catch (Exception $e) {
            echo "Error in init ecommerce module tables." . PHP_EOL;
        }
    }

    public function setUp()
    {
        $this->truncateDb();
        parent::setUp();
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function truncateDb()
    {
        $sql = "
        SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
        TRUNCATE ec_category;
        TRUNCATE ec_discount_group;
        TRUNCATE ec_discount_group_product;
        TRUNCATE ec_order;
        TRUNCATE ec_order_item;
        TRUNCATE ec_parameter;
        TRUNCATE ec_parameter_category;
        TRUNCATE ec_parameter_value;
        TRUNCATE ec_parameter_value_product_variation;
        TRUNCATE ec_payment;
        TRUNCATE ec_product;
        TRUNCATE ec_product_category;
        TRUNCATE ec_product_variation;
        TRUNCATE ec_stock;
        TRUNCATE ec_stock_balance;
        ";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        self::destroyApplication();
        parent::tearDownAfterClass();
    }

    /**
     * Creating Yii2 application
     * @throws \yii\base\InvalidConfigException
     */
    protected static function mockApplication()
    {
        new Application([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'runtimePath' => __DIR__ . '/runtime',
        ]);
    }


    /**
     * Drop application
     */
    protected static function destroyApplication()
    {
        Yii::$app = null;
    }
}