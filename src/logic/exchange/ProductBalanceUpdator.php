<?php


namespace floor12\ecommerce\logic\exchange;


use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\entity\Stock;
use floor12\ecommerce\models\entity\StockBalance;
use yii\base\ErrorException;

class ProductBalanceUpdator
{
    /**
     * @var string
     */
    protected $stockUID;
    /**
     * @var string
     */
    protected $stockName;
    /**
     * @var int
     */
    protected $productVarionId;
    /**
     * @var int
     */
    protected $balance;

    /**
     * @var Stock
     */
    protected $stock;
    /**
     * @var StockBalance
     */
    protected $stockBalance;

    /**
     * ProductBalanceUpdator constructor.
     * @param string $stockUID
     * @param string $stockName
     * @param int $balance
     */
    public function __construct(string $stockUID, string $stockName, int $productVarionId, int $balance)
    {
        if ($balance < 0)
            throw new ErrorException('Balance value error.');
        $this->balance = $balance;
        $this->stockName = $stockName;
        $this->stockUID = $stockUID;
        $this->productVarionId = $productVarionId;
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function update()
    {
        if (empty($this->stock))
            $this->findOrCreateStock();

        return $this->updateStockBalance();
    }

    /**
     * @return bool
     */
    protected function updateStockBalance()
    {
        $this->stockBalance = StockBalance::find()
            ->byStockId($this->stock->id)
            ->byProductVarioationId($this->productVarionId)
            ->one();

        if (!is_object($this->stockBalance))
            $this->stockBalance = new StockBalance([
                'stock_id' => $this->stock->id,
                'product_variation_id' => $this->productVarionId
            ]);

        $this->stockBalance->balance = $this->balance;
        return $this->stockBalance->save();
    }


    /**
     * @return Product
     * @throws ErrorException
     */
    protected function findOrCreateStock()
    {
        $this->stock = Stock::find()
            ->where(['external_id' => $this->stockUID])
            ->one();

        if (is_object($this->stock))
            return $this->stock;

        $this->stock = new Stock([
            'title' => $this->stockName,
            'external_id' => $this->stockUID,
        ]);

        if (!$this->stock->save())
            throw new ErrorException('Error stock saving: ' . print_r($this->product->getFirstErrors(), 1));

        return $this->stock;
    }

}