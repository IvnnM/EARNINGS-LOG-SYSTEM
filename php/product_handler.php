<?php

// Include necessary files
include_once __DIR__ . '/../connection.php'; 
include_once __DIR__ . '/get_products.php';

// ProductHandler class inherits from DatabaseHandler class
class ProductHandler extends DatabaseHandler
{
    // Inheritance: ProductHandler inherits from DatabaseHandler

    // Abstraction: Method to add a product
    public function addProduct(Product $product)
    {
        // Extract information from the Product object
        $productName = $product->getProductName();
        $unitPrice = $product->getUnitPrice();
        $stockQuantity = $product->getStockQuantity();

        // Check if the product with the same name already exists
        $checkQuery = "SELECT * FROM products_table WHERE product_name = '$productName'";
        $checkResult = $this->con->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Product with the same name already exists
            return "exists";
        } else {
            // Insert the new product into the database
            $sql = "INSERT INTO products_table (product_name, unit_price, stock_quantity) VALUES ('$productName', '$unitPrice', '$stockQuantity')";

            if ($this->con->query($sql) === TRUE) {
                return "success";
            } else {
                return "Error: " . $sql . "<br>" . $this->con->error;
            }
        }
    }

    // Abstraction: Method to update a product
    public function updateProduct($productId, $productName, $unitPrice, $stockQuantity)
    {
        // Update the product information in the database
        $sql = "UPDATE products_table SET product_name='$productName', unit_price='$unitPrice', stock_quantity='$stockQuantity' WHERE product_id='$productId'";

        if ($this->con->query($sql) === TRUE) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $this->con->error;
        }
    }

    // Abstraction: Method to delete a product
    public function deleteProduct($productId)
    {
        // Delete the product from the database
        $sql = "DELETE FROM products_table WHERE product_id='$productId'";

        if ($this->con->query($sql) === TRUE) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $this->con->error;
        }
    }

    // Abstraction: Method to add stock
    public function addStock(Product $product, $quantity)
    {
        // Add stock to an existing product
        $productId = $product->getProductId();
        $result = $this->con->query("SELECT stock_quantity FROM products_table WHERE product_id = $productId");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentStock = $row['stock_quantity'];

            // Calculate new stock quantity and update the database
            $newStock = $currentStock + $quantity;
            $this->con->query("UPDATE products_table SET stock_quantity = $newStock WHERE product_id = $productId");

            return 'success';
        } else {
            return 'error';
        }
    }

    // Method to close the database connection
    public function closeConnection()
    {
        $this->con->close();
    }
}
?>
