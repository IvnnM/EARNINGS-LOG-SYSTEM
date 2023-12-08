<?php
include '../connection.php';

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

class CartHandler extends DatabaseHandler
{
    // Inheritance: CartHandler inherits from DatabaseHandler

    // Abstraction: Method to add sold items to the cart
    public function addSoldItems($data)
    {
        $productId = $data->product_id;
        $quantity = $data->quantity;

        $checkStockQuery = "SELECT stock_quantity FROM products_table WHERE product_id = $productId";
        $checkStockResult = $this->con->query($checkStockQuery);

        if ($checkStockResult->num_rows > 0) {
            $row = $checkStockResult->fetch_assoc();
            $stockQuantity = $row['stock_quantity'];

            if ($stockQuantity >= $quantity) {
                $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity - $quantity WHERE product_id = $productId";
                $this->con->query($updateProductsQuery);

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

        $fetchLatestCartItemQuery = "SELECT id, quantity FROM cart_table WHERE product_id = $productId ORDER BY timestamp DESC LIMIT 1";
        $fetchLatestCartItemResult = $this->con->query($fetchLatestCartItemQuery);

        if ($fetchLatestCartItemResult->num_rows > 0) {
            $row = $fetchLatestCartItemResult->fetch_assoc();
            $latestCartItemId = $row['id'];
            $quantityToDelete = $row['quantity'];

            $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity + $quantityToDelete WHERE product_id = $productId";
            $this->con->query($updateProductsQuery);

            $deleteCartQuery = "DELETE FROM cart_table WHERE id = $latestCartItemId";
            $this->con->query($deleteCartQuery);

            return "success";
        } else {
            return "No item found in cart";
        }
    }
}

// Instantiate CartHandler
$cartHandler = new CartHandler($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action)) {
        $action = $data->action;

        switch ($action) {
            case "addSoldItems":
                echo $cartHandler->addSoldItems($data);
                break;

            case "deleteSoldItems":
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
