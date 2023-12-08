<?php

// connection.php
class DatabaseHandler {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'db_sarisaristore';

    public $con;

    public function __construct() {
        $this->con = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }
}

?>
