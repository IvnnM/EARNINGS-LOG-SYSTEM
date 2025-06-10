<?php
// Start session
session_start();
date_default_timezone_set('Asia/Manila');
$todayDate = date("Y-m-d");
include 'connection.php';

// Create an instance of the DatabaseHandler class
$databaseHandler = new DatabaseHandler();

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT username FROM user_table
            WHERE user_id = {$_SESSION["user_id"]}";

    $result = $databaseHandler->con->query($sql);

    if ($result) {
        $user = $result->fetch_assoc();
    } else {
        // Add this to see if there's an error in your query
        echo "Query failed: " . $databaseHandler->con->error;
    }
}

function getRecordCount($table, $condition = "") {
    global $databaseHandler; // Access the global variable $databaseHandler
    $con = $databaseHandler->con; // Access the connection property

    $query = "SELECT COUNT(*) AS total_records FROM $table $condition";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total_records'];
}

// Example usage:
$total_outofstock_products = getRecordCount("products_table", "WHERE stock_quantity <= 0");
$total_products = getRecordCount("products_table");
$total_today_transactions = getRecordCount("cart_table", "WHERE DATE(timestamp) = '$todayDate'");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari Store Inventory</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!--Sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
    <link rel="stylesheet" href="./css/index_style.css">

</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
    
<div class="container">
    <div class="row align-items-start">
        
        <!-- Left Container -->
        <div class="col overflow-y-auto" id="left-container">
            <div class="d-block px-2 pt-2 px-3">
                <h1>SARI-SARI STORE</h1>
                <h6 style="margin-bottom: 0;">Earnings Log System</h6>
            </div>
            <div class="d-block p-2 px-3">
             <div class="row">
                
                <a id="dropdown" class="nav-link col-12 p-2 mb-2 dropdown-toggle border rounded text-decoration-none" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Welcome, <span><?= htmlspecialchars($user["username"]) ?>! </span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="width: 200px; max-height: 200px; overflow-y: auto;">
                    <li><a class="dropdown-item" href="admin_page.php">Create Report</a></li>
                    <li><a class="dropdown-item" href="#header">Log History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" id="logout" href="./php/logout.php">Logout</a></li>
                </ul>



                <!-- Total Products -->
                <a id="overview" href="#product" class="nav-link col-12 p-2 mb-1 border rounded bg-success text-decoration-none">
                    <span><?php echo $total_products; ?></span> <h5>Total Products</h5>
                </a>

                <!-- Out of Stocks -->
                <a id="overview" href="#outStock" class="nav-link col-12 p-2 mb-1 border rounded bg-danger text-decoration-none ">
                    <span><?php echo $total_outofstock_products; ?></span> <h5>Out of Stocks</h5>
                </a>

                <!-- Today's Sold Items -->
                <a id="overview" href="#soldItems" class="nav-link col-12 p-2 mb-1 border rounded bg-primary text-decoration-none">
                    <span><?php echo $total_today_transactions; ?></span> <h5>Today's Sold Items</h5>
                </a>
              </div>
            </div>
        </div>
        
        <!-- Right Container -->
        <div class="col overflow-y-auto p-2" id="right-container">
          <div class="d-block p-2 px-3" id="header">
            <h6> DASHBOARD</h6>
          </div>
          <div class="d-block p-2 px-3 m-3">
              <h2 id="earnings">Earnings Log History</h2>
              <div class="overflow-y-auto p-2 px-3" id="cartList" style="background-color: #cfe2ff; border-radius:8px; height: 77vh;">
                <?php
                $databaseHandler = new DatabaseHandler();

                $sql = "SELECT * FROM record_sale_table ORDER BY record_sale_id DESC";
                $result = $databaseHandler->con->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='table table-primary' id='recordSaleTable'>";
                    echo '
                        <thead>
                            <tr>
                                <th scope="col">Record Sale ID</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Sale Date</th>
                                <th scope="col">Total Items</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Timestamp</th>
                            </tr>
                        </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["record_sale_id"] . "</td>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["sale_date"] . "</td>";
                        echo "<td>" . $row["total_items"] . "</td>";
                        echo "<td>" . $row["total_price"] . "</td>";
                        echo "<td>" . $row["timestamp"] . "</td>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }

                $databaseHandler->con->close();
                ?>

              </div>
          </div>
          <div class="d-block p-2 px-3 m-3">
            <h2 id="product">Products</h2>
            <div class="overflow-y-auto p-2 px-3" id="productList" style="background-color: #cfe2ff; border-radius:8px; height: 55vh;">
                <?php
                $databaseHandler = new DatabaseHandler();

                $sql = "SELECT * FROM products_table";
                $result = $databaseHandler->con->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='table table-primary' id='productTable'>";
                    echo '
                        <thead>
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Stock</th>
                            </tr>
                        </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["product_id"] . "</td>";
                        echo "<td>" . $row["product_name"] . "</td>";
                        echo "<td>" . $row["unit_price"] . "</td>";
                        echo "<td>" . $row["stock_quantity"] . "</td>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }

                $databaseHandler->con->close();
                ?>
            </div>
          </div>
          <div class="d-block p-2 px-3 m-3">
            <h2 id="outStock">Out of stock</h2>
            <div class="overflow-y-auto p-2 px-3" id="productList" style="background-color: #cfe2ff; border-radius:8px; height: 55vh;">

                <!-- Product list will be displayed here -->
                <?php
                $databaseHandler = new DatabaseHandler();

                $sql = "SELECT * FROM products_table WHERE stock_quantity <= 0";
                $result = $databaseHandler->con->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='table table-primary' id='productTable'>";
                    echo '
                        <thead>
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Stock</th>
                            </tr>
                        </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["product_id"] . "</td>";
                        echo "<td>" . $row["product_name"] . "</td>";
                        echo "<td>" . $row["unit_price"] . "</td>";
                        echo "<td>" . $row["stock_quantity"] . "</td>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }

                $databaseHandler->con->close();
                ?>
            </div>

          </div>
          <div class="d-block p-2 px-3 m-3">
              <h2 id="soldItems">Sold Items</h2>
              <div class="overflow-y-auto p-2 px-3" id="cartList" style="background-color: #cfe2ff; border-radius:8px; height: 55vh;">

                <!-- Cart list will be displayed here -->
                <?php
                // Create an instance of the DatabaseHandler class
                $databaseHandler = new DatabaseHandler();

                date_default_timezone_set('Asia/Manila');
                // Initialize $todayDate with the current date as a default
                $todayDate = date("Y-m-d");

                // Check if a date is provided by the user
                if (isset($_GET['selectedDate'])) {
                    // Use the provided date if available and convert it to the correct format
                    $selectedDate = $_GET['selectedDate'];
                    $todayDate = date("Y-m-d", strtotime($selectedDate));
                }

                // Modify your SQL query to filter records based on the selected date
                $sql = "SELECT cart_table.id, products_table.product_name, cart_table.unit_price, cart_table.quantity, cart_table.timestamp
                FROM cart_table
                INNER JOIN products_table ON cart_table.product_id = products_table.product_id
                WHERE DATE(cart_table.timestamp) = '$todayDate'";

                $result = $databaseHandler->con->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='table table table-primary overflow-y-auto' id='cartTable'>";
                    echo '
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Product</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>';
                    echo '<tbody class="table-group-divider">'; // You may not need to create a new tbody for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["product_name"] . "</td>"; 
                        echo "<td>" . $row["unit_price"] . "</td>";
                        echo "<td>" . $row["quantity"] . "</td>";
                        echo "<td>" . $row["timestamp"] . "</td>";
                        echo '</tr>';
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<br>No records found for the selected date";
                }
                ?>
              </div>
          </div>
          
        </div>
        
    </div>
</div>
<?php else: ?>
    <button id="loginBtn" type="button" class="btn btn-danger" onclick="location.href='login.php'">
        <i class="fa fa-lock"></i> Login
    </button>
<?php endif; ?>

</body>
</html>
