<?php
// CRUD for Product Handling

class DatabaseHandler
{
    protected $con;

    public function __construct($db)
    {
        $this->con = $db;

        // Check connection
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }

    public function closeConnection()
    {
        $this->con->close();
    }
}

class ProductHandler extends DatabaseHandler
{
    // Inheritance: ProductHandler inherits from DatabaseHandler

    // Abstraction: Method to add a product
    public function addProduct($productName, $unitPrice, $stockQuantity)
    {
        $checkQuery = "SELECT * FROM products_table WHERE product_name = '$productName'";
        $checkResult = $this->con->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            return "exists";
        } else {
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
        $sql = "DELETE FROM products_table WHERE product_id='$productId'";

        if ($this->con->query($sql) === TRUE) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $this->con->error;
        }
    }

    // Abstraction: Method to add stock
    public function addStock($productId, $quantity)
    {
        $result = $this->con->query("SELECT stock_quantity FROM products_table WHERE product_id = $productId");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentStock = $row['stock_quantity'];

            $newStock = $currentStock + $quantity;
            $this->con->query("UPDATE products_table SET stock_quantity = $newStock WHERE product_id = $productId");

            return 'success';
        } else {
            return 'error';
        }
    }
}
?>
