<?php

// select_product_id.php
class SelectProductIDHandler extends DatabaseHandler
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductIDs()
    {
        $productIDs = [];

        $result = $this->con->query("SELECT product_id FROM products_table");

        while ($row = $result->fetch_assoc()) {
            $productIDs[] = $row["product_id"];
        }

        return $productIDs;
    }
}
?>
