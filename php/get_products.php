<?php

class Product
{
    protected $product_id;
    protected $product_name;
    protected $unit_price;
    protected $stock_quantity;

    public function __construct($product_id, $product_name, $unit_price, $stock_quantity)
    {
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->unit_price = $unit_price;
        $this->stock_quantity = $stock_quantity;
    }

    public function display()
    {
        echo "Product ID: {$this->product_id}, Name: {$this->product_name}, Price: {$this->unit_price}, Quantity: {$this->stock_quantity}";
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function getStockQuantity()
    {
        return $this->stock_quantity;
    }
}

?>
