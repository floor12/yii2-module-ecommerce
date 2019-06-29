<?php


namespace floor12\ecommerce\logic;


use floor12\ecommerce\models\Order;

class RecepientCreator
{
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var array
     */
    protected $items = [];

    /**
     * RecepientCreator constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        foreach ($this->order->orderItems as $item) {
            $this->items[] = [
                'label' => $item->item->title,
                'price' => $item->price,
                'amount' => $item->sum,
                'quantity' => $item->quantity,
                "measurementUnit" => "шт"
            ];
        };
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * @return string
     */
    public function getItemsJson()
    {
        return json_encode($this->items);
    }


}