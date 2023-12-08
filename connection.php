<?php
// connection.php

// This class represents a DatabaseHandler, encapsulating database connection functionality.
class DatabaseHandler {
    // Encapsulation: Private properties to hide implementation details.
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'db_sarisaristore';

    // Public property for storing the database connection.
    public $con;

    // Constructor: Initializes the database connection when an object is created.
    public function __construct() {
        // Encapsulation: Using private properties within the class.
        // Creating a new MySQLi (MySQL Improved) object for database connection.
        $this->con = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Checking for a successful connection.
        if ($this->con->connect_error) {
            // Aborting the program if the connection fails.
            die("Connection failed: " . $this->con->connect_error);
        }
    }
}

?>
