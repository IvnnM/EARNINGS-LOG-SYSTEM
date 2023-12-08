<?php
include '../connection.php';

class SaleHandler
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;

        // Check connection
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }

    public function processSale($selectedDate, $userId)
    {
        // Fetch cart items for the selected date
        $cartSql = "SELECT * FROM cart_table WHERE DATE(timestamp) = '$selectedDate'";
        $cartResult = $this->con->query($cartSql);

        if ($cartResult) {
            // Check if the cart is not empty
            if ($cartResult->num_rows > 0) {
                // Initialize variables for total items and total price
                $totalItems = 0;
                $totalPrice = 0;

                // Iterate through cart items
                while ($cartRow = $cartResult->fetch_assoc()) {
                    $totalItems += $cartRow['quantity'];
                    $totalPrice += $cartRow['unit_price'] * $cartRow['quantity'];
                }

                // Insert into record_sale_table
                $insertSaleSql = "INSERT INTO record_sale_table (user_id, sale_date, total_items, total_price, timestamp) 
                                  VALUES ('$userId', '$selectedDate', $totalItems, $totalPrice, NOW())";

                if ($this->con->query($insertSaleSql) === TRUE) {
                    // Clear the cart for the selected date
                    $clearCartSql = "DELETE FROM cart_table WHERE DATE(timestamp) = '$selectedDate'";
                    $this->con->query($clearCartSql);

                    return 'success';
                } else {
                    return "Error: " . $insertSaleSql . "<br>" . $this->con->error;
                }
            } else {
                return 'Cart is empty.';
            }
        } else {
            return 'Error executing the cart query.';
        }
    }

    public function closeConnection()
    {
        $this->con->close();
    }
}

// Usage example:
session_start();
$userId = $_SESSION['user_id']; // Assuming user_id is stored in the session
$saleHandler = new SaleHandler($con);
$selectedDate = $_GET['selectedDate'];
$result = $saleHandler->processSale($selectedDate, $userId);
echo $result;
$saleHandler->closeConnection();
?>
