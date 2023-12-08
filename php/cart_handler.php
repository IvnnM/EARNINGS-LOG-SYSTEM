<?php
// Include the connection.php file
include_once __DIR__ . '/../connection.php';

// CartHandler class extends DatabaseHandler class
class CartHandler extends DatabaseHandler
{
    // Abstraction: Method to add sold items to the cart
    public function addSoldItems($data)
    {
        // Encapsulation: Accessing data through the object and not directly
        $productId = $data->product_id;
        $quantity = $data->quantity;

        // Fetch stock quantity from products_table
        $checkStockQuery = "SELECT stock_quantity FROM products_table WHERE product_id = $productId";
        $checkStockResult = $this->con->query($checkStockQuery);

        if ($checkStockResult->num_rows > 0) {
            $row = $checkStockResult->fetch_assoc();
            $stockQuantity = $row['stock_quantity'];

            // Encapsulation: Modifying stock quantity through the object
            if ($stockQuantity >= $quantity) {
                // Update stock quantity in products_table
                $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity - $quantity WHERE product_id = $productId";
                $this->con->query($updateProductsQuery);

                // Insert sold items into cart_table
                $insertCartQuery = "INSERT INTO cart_table (product_id, unit_price, quantity, timestamp) 
                                    VALUES ($productId, $data->unit_price, $quantity, NOW())";
                $this->con->query($insertCartQuery);

                return "success";
            } else {
                return "Insufficient stock";
            }
        } else {
            return "Product not found";
        }
    }

    // Abstraction: Method to delete sold items from the cart
    public function deleteSoldItems($data)
    {
        $productId = $data->product_id;

        // Fetch the latest item from cart_table for the specified product
        $fetchLatestCartItemQuery = "SELECT id, quantity FROM cart_table WHERE product_id = $productId ORDER BY timestamp DESC LIMIT 1";
        $fetchLatestCartItemResult = $this->con->query($fetchLatestCartItemQuery);

        if ($fetchLatestCartItemResult->num_rows > 0) {
            $row = $fetchLatestCartItemResult->fetch_assoc();
            $latestCartItemId = $row['id'];
            $quantityToDelete = $row['quantity'];

            // Update stock quantity in products_table
            $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity + $quantityToDelete WHERE product_id = $productId";
            $this->con->query($updateProductsQuery);

            // Delete the latest item from cart_table
            $deleteCartQuery = "DELETE FROM cart_table WHERE id = $latestCartItemId";
            $this->con->query($deleteCartQuery);

            return "success";
        } else {
            return "No item found in cart";
        }
    }

    // Encapsulation: Method to close the database connection
    public function closeConnection()
    {
        $this->con->close();
    }
}

// Start the session
session_start();

// Instantiate CartHandler
global $con;
$cartHandler = new CartHandler($con);

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action)) {
        $action = $data->action;

        // Switch statement for different actions
        switch ($action) {
            case "addSoldItems":
                // Call the addSoldItems method
                echo $cartHandler->addSoldItems($data);
                break;

            case "deleteSoldItems":
                // Call the deleteSoldItems method
                echo $cartHandler->deleteSoldItems($data);
                break;

            default:
                echo "Invalid action";
                break;
        }
    } else {
        echo "Action not set";
    }
} else {
    echo "Invalid request method";
}

// Close the database connection
$cartHandler->closeConnection();
?>
