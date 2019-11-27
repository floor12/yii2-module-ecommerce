<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\ecommerce\tests\unit;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\entity\Parameter;
use floor12\ecommerce\models\entity\ParameterValue;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\ProductVariation;
use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\enum\ParameterType;
use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\forms\ProductVariationForm;
use floor12\ecommerce\tests\TestCase;
use Yii;
use yii\base\ErrorException;

class ProductVariationFormTest extends TestCase
{
    /**
     * @var Product
     */
    public $product;
    /**
     * @var Parameter[]
     */
    public $parameters = [];
    /**
     * @var Category[]
     */
    public $categories = [];
    /**
     * @var Stock[]
     */
    public $stocks = [];


    /**
     *
     */
    protected function createParameters()
    {
        $this->parameters[] = new Parameter([
            'title' => 'Width',
            'type_id' => ParameterType::SLIDER,
            'unit' => 'mm',
        ]);

        $this->parameters[] = new Parameter([
            'title' => 'Height',
            'type_id' => ParameterType::SLIDER,
            'unit' => 'mm',
        ]);


        $this->parameters[] = new Parameter([
            'title' => 'Color',
            'type_id' => ParameterType::CHECKBOX,
        ]);

        $this->parameters[] = new Parameter([
            'title' => 'Country',
            'type_id' => ParameterType::CHECKBOX,
        ]);

        foreach ($this->parameters as $parameter)
            $this->assertTrue($parameter->save());
    }

    protected function createCategories()
    {
        $this->categories[] = new Category([
            'id' => 1,
            'title' => 'Root category 1',
            'parameter_ids' => [1]
        ]);

        $this->categories[] = new Category([
            'id' => 2,
            'title' => 'Root category 2',
            'parameter_ids' => [2]
        ]);

        $this->categories[] = new Category([
            'id' => 3,
            'title' => 'Sub category 1',
            'parent_id' => 1,
            'parameter_ids' => [3]
        ]);

        foreach ($this->categories as $category)
            $this->assertTrue($category->save());
    }

    protected function createStocks()
    {
        $this->stocks[] = new Stock([
            'id' => 1,
            'title' => 'Stock 1',
        ]);

        $this->stocks[] = new Stock([
            'id' => 2,
            'title' => 'Stock 2',
        ]);

        $this->stocks[] = new Stock([
            'id' => 3,
            'title' => 'Stock 3',
        ]);

        foreach ($this->stocks as $stock)
            $this->assertTrue($stock->save());
    }

    /**
     *
     */
    protected function createProduct()
    {
        $this->product = new Product([
            'title' => 'Test product',
            'subtitle' => 'Test product',
            'status' => Status::ACTIVE,
        ]);

        $this->assertTrue($this->product->save());
    }

    public function testProductNotSet()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('This variation has no product.');
        $variation = new ProductVariation();
        $form = new ProductVariationForm($variation);
    }

    public function testProductSaveMainData()
    {
        $this->createProduct();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $external_id = rand(99, 999);
        $price_0 = rand(99, 999);
        $price_1 = rand(99, 999);
        $price_2 = rand(99, 999);

        $dataToLoad = ['ProductVariationForm' => [
            'external_id' => $external_id,
            'price_0' => $price_0,
            'price_1' => $price_1,
            'price_2' => $price_2,
        ]];

        $form->load($dataToLoad);

        $this->assertTrue($form->save());
        $this->assertFalse($variation->isNewRecord);
    }

    public function testFindParamsWithNoCategories()
    {
        $this->createProduct();
        $this->createParameters();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $this->assertEquals(4, sizeof($form->parameters));
    }

    public function testFindParamsWithCategories()
    {
        $this->createProduct();
        $this->createParameters();
        $this->createCategories();
        $this->product->category_ids = [3];
        $this->assertTrue($this->product->save());

        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $this->assertEquals(3, sizeof($form->parameters));
        $this->assertEquals(1, $form->parameters[1]->id);
        $this->assertEquals(3, $form->parameters[3]->id);
        $this->assertEquals(4, $form->parameters[4]->id);
    }


    public function testLoadParamsValues()
    {
        $this->createProduct();
        $this->createParameters();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $external_id = rand(99, 999);
        $price_0 = rand(99, 999);
        $price_1 = rand(99, 999);
        $price_2 = rand(99, 999);

        $dataToLoad = ['ProductVariationForm' => [
            'external_id' => $external_id,
            'price_0' => $price_0,
            'price_1' => $price_1,
            'price_2' => $price_2,
            'parameterValues' => [
                '1' => 100,
                '2' => 200,
            ]
        ]];
        $form->load($dataToLoad);
        $this->assertTrue($form->save());

        $variation->refresh();
        $form = new ProductVariationForm($variation);

        $this->assertEquals(100, $form->parameterValues[1]);
        $this->assertEquals(200, $form->parameterValues[2]);
    }


    public function testSaveParams()
    {
        $this->createProduct();
        $this->createParameters();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $external_id = rand(99, 999);
        $price_0 = rand(99, 999);
        $price_1 = rand(99, 999);
        $price_2 = rand(99, 999);

        $dataToLoad = ['ProductVariationForm' => [
            'external_id' => $external_id,
            'price_0' => $price_0,
            'price_1' => $price_1,
            'price_2' => $price_2,
            'parameterValues' => [
                '1' => 100,
                '2' => 200,
            ]
        ]];

        $form->load($dataToLoad);

        $this->assertTrue($form->save());

        $this->assertEquals(2, ParameterValue::find()->count());
        $this->assertEquals(2, Yii::$app
            ->db->createCommand('SELECT count(*) FROM ec_parameter_value_product_variation')->queryScalar());
    }

    public function testFindStocks()
    {
        $this->createProduct();
        $this->createStocks();

        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $this->assertEquals(3, sizeof($form->stocks), print_r($form->stocks, 1));
        $this->assertEquals(1, $form->stocks[1]->id, print_r($form->stocks, 1));
        $this->assertEquals(2, $form->stocks[2]->id);
        $this->assertEquals(3, $form->stocks[3]->id);
    }

    public function testSaveStockBalances()
    {
        Yii::$app->getModule('shop'); //init i18n
        $this->createProduct();
        $this->createParameters();
        $this->createStocks();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $external_id = rand(99, 999);
        $price_0 = rand(99, 999);
        $price_1 = rand(99, 999);
        $price_2 = rand(99, 999);

        $dataToLoad = ['ProductVariationForm' => [
            'external_id' => $external_id,
            'price_0' => $price_0,
            'price_1' => $price_1,
            'price_2' => $price_2,
            'stockBalances' => [
                '1' => 100,
                '2' => 200,
            ]
        ]];
        $form->load($dataToLoad);
        $this->assertTrue($form->save());

        $variation->refresh();
        $form = new ProductVariationForm($variation);

        $this->assertEquals(100, $form->stockBalances[1]);
        $this->assertEquals(200, $form->stockBalances[2]);
    }

    public function testLoadSavedStockBalances()
    {
        Yii::$app->getModule('shop'); //init i18n
        $this->createProduct();
        $this->createParameters();
        $this->createStocks();
        $variation = new ProductVariation(['product_id' => $this->product->id]);
        $form = new ProductVariationForm($variation);

        $external_id = rand(99, 999);
        $price_0 = rand(99, 999);
        $price_1 = rand(99, 999);
        $price_2 = rand(99, 999);

        $dataToLoad = ['ProductVariationForm' => [
            'external_id' => $external_id,
            'price_0' => $price_0,
            'price_1' => $price_1,
            'price_2' => $price_2,
            'stockBalances' => [
                '1' => 100,
                '2' => 200,
            ]
        ]];
        $form->load($dataToLoad);
        $this->assertTrue($form->save());

        $variation->refresh();
        $form = new ProductVariationForm($variation);

        $this->assertEquals(100, $form->parameterValues[1]);
        $this->assertEquals(200, $form->parameterValues[2]);
        $this->assertEquals(0, $form->parameterValues[3]);
    }


}