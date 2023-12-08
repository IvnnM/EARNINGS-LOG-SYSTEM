<?php
// sale_handler.php

// Inclusion of database connection file
include_once __DIR__ . '/../connection.php';

// Inheritance: SaleHandler inherits from DatabaseHandler
class SaleHandler extends DatabaseHandler
{
    // Abstraction: Method to process a sale for a given date and user
    public function processSale($selectedDate, $userId)
    {
        // Fetch cart items for the selected date
        $cartSql = "SELECT * FROM cart_table WHERE DATE(timestamp) = ?";
        $cartStmt = $this->con->prepare($cartSql);

        if (!$cartStmt) {
            die("Error preparing cart query: " . $this->con->error);
        }

        $cartStmt->bind_param("s", $selectedDate);
        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();

        if (!$cartResult) {
            die("Error executing cart query: " . $this->con->error);
        }

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
                              VALUES (?, ?, ?, ?, NOW())";

            $insertSaleStmt = $this->con->prepare($insertSaleSql);

            if (!$insertSaleStmt) {
                die("Error preparing sale query: " . $this->con->error);
            }

            $insertSaleStmt->bind_param("issd", $userId, $selectedDate, $totalItems, $totalPrice);
            $insertSaleStmt->execute();

            // Clear the cart for the selected date
            $clearCartSql = "DELETE FROM cart_table WHERE DATE(timestamp) = ?";
            $clearCartStmt = $this->con->prepare($clearCartSql);

            if (!$clearCartStmt) {
                die("Error preparing clear cart query: " . $this->con->error);
            }

            $clearCartStmt->bind_param("s", $selectedDate);
            $clearCartStmt->execute();

            return 'success';
        } else {
            return 'Cart is empty.';
        }
    }

    // Abstraction: Method to close the database connection
    public function closeConnection()
    {
        $this->con->close();
    }
}

// Start the session
session_start();

// Instantiate SaleHandler
global $con; // Assuming $con is your database connection
$saleHandler = new SaleHandler($con);

// Check if the request method is GET and 'selectedDate' is set in the parameters
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selectedDate'])) {
    // Get the user ID from the session or set it to null
    $userId = $_SESSION['user_id'] ?? null;
    $selectedDate = $_GET['selectedDate'];

    // Check if the user ID is not null
    if ($userId !== null) {
        // Call the processSale method with the selected date and user ID
        $result = $saleHandler->processSale($selectedDate, $userId);
        echo $result;
    } else {
        echo 'Invalid session or parameters.';
    }
} else {
    echo "Invalid request method or parameters.";
}

// Close the database connection
$saleHandler->closeConnection();
?>
