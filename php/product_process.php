<?php
// Product Handling process
// Start the session
session_start();

// Include your database connection file
include_once __DIR__ . '/../connection.php'; 

// Include the Product class file
include_once __DIR__ . '/get_products.php';

// Include the ProductHandler class file
include_once 'product_handler.php'; 

// Create an instance of ProductHandler
global $con;
$productHandler = new ProductHandler($con);


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action)) {
        $action = $data->action;

        switch ($action) {
            case "addProduct":
                handleAction($productHandler, 'addProduct', $data);
                break;

            case "updateProduct":
                handleAction($productHandler, 'updateProduct', $data);
                break;

            case "deleteProduct":
                handleAction($productHandler, 'deleteProduct', $data);
                break;

            case "addStock":
                handleAction($productHandler, 'addStock', $data);
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

// Function to handle various actions
function handleAction($handler, $action, $data) {
    $result = "";

    switch ($action) {
        case 'addProduct':
            $result = handleAddProduct($handler, $data);
            break;

        case 'updateProduct':
            $result = handleUpdateProduct($handler, $data);
            break;

        case 'deleteProduct':
            $result = handleDeleteProduct($handler, $data);
            break;

        case 'addStock':
            $result = handleAddStock($handler, $data);
            break;
            
        default:
            echo "Invalid action";
            break;
    }

    echo $result;
}

// Function to handle the "addProduct" action
function handleAddProduct($handler, $data) {
    if (isset($data->product_name) && isset($data->unit_price) && isset($data->stock_quantity)) {
        $productName = $data->product_name;
        $unitPrice = $data->unit_price;
        $stockQuantity = $data->stock_quantity;

        // Create a Product object
        $product = new Product(null, $productName, $unitPrice, $stockQuantity);

        // Call the addProduct method with the Product object
        return $handler->addProduct($product);
    } else {
        return "Incomplete data for adding a product";
    }
}


// Function to handle the "updateProduct" action
function handleUpdateProduct($handler, $data) {
    if (isset($data->product_id) && isset($data->product_name) && isset($data->unit_price) && isset($data->stock_quantity)) {
        $productId = $data->product_id;
        $productName = $data->product_name;
        $unitPrice = $data->unit_price;
        $stockQuantity = $data->stock_quantity;

        // Call the updateProduct method with the parameters
        return $handler->updateProduct($productId, $productName, $unitPrice, $stockQuantity);
    } else {
        return "Incomplete data for updating a product";
    }
}

// Function to handle the "deleteProduct" action
function handleDeleteProduct($handler, $data) {
    if (isset($data->product_id)) {
        $productId = $data->product_id;

        // Call the deleteProduct method with the product ID
        return $handler->deleteProduct($productId);
    } else {
        return "Incomplete data for deleting a product";
    }
}

// Function to handle the "addStock" action
function handleAddStock($handler, $data) {
    if (isset($data->product_id) && isset($data->quantity)) {
        $productId = $data->product_id;
        $quantity = $data->quantity;

        // Create a Product object with the product ID
        $product = new Product($productId, '', 0, 0);

        // Call the addStock method with the Product object and quantity
        return $handler->addStock($product, $quantity);
    } else {
        return "Incomplete data for adding stock";
    }
}


// Close the database connection
$productHandler->closeConnection();
?>
