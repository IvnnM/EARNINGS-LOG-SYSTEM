<?php

// select_product_id.php

// Inheritance: SelectProductIDHandler inherits from DatabaseHandler
class SelectProductIDHandler extends DatabaseHandler
{
    // Constructor calling the parent constructor
    public function __construct()
    {
        parent::__construct();
    }

    // Abstraction: Method to retrieve product IDs from the database
    public function getProductIDs()
    {
        // Array to store product IDs
        $productIDs = [];

        // Query to select product IDs from the database
        $result = $this->con->query("SELECT product_id FROM products_table");

        // Fetching product IDs and adding them to the array
        while ($row = $result->fetch_assoc()) {
            $productIDs[] = $row["product_id"];
        }

        // Returning the array of product IDs
        return $productIDs;
    }
}
?>
