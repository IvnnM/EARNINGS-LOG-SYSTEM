<?php

// Product class represents a product with encapsulated properties and methods
class Product
{
    // Encapsulation: Properties are declared with protected visibility
    protected $product_id;
    protected $product_name;
    protected $unit_price;
    protected $stock_quantity;

    // Abstraction: Constructor provides a way to create an object with initial data
    public function __construct($product_id, $product_name, $unit_price, $stock_quantity)
    {
        // Encapsulation: Initialize properties through the constructor
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->unit_price = $unit_price;
        $this->stock_quantity = $stock_quantity;
    }

    // Abstraction: Method to display product information
    public function display()
    {
        // Encapsulation: Access properties through methods to display information
        echo "Product ID: {$this->product_id}, Name: {$this->product_name}, Price: {$this->unit_price}, Quantity: {$this->stock_quantity}";
    }

    // Encapsulation: Getter method to retrieve product ID
    public function getProductId()
    {
        return $this->product_id;
    }

    // Encapsulation: Getter method to retrieve product name
    public function getProductName()
    {
        return $this->product_name;
    }

    // Encapsulation: Getter method to retrieve unit price
    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    // Encapsulation: Getter method to retrieve stock quantity
    public function getStockQuantity()
    {
        return $this->stock_quantity;
    }
}

?>
