<?php
include_once __DIR__ . '/../connection.php';

class SaleHandler extends DatabaseHandler
{
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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selectedDate'])) {
    $userId = $_SESSION['user_id'] ?? null;
    $selectedDate = $_GET['selectedDate'];

    if ($userId !== null) {
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
