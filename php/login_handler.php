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

class LoginHandler extends DatabaseHandler
{
    // Inheritance: LoginHandler inherits from DatabaseHandler

    // Abstraction: Method to authenticate user
    public function authenticateUser($username, $password)
    {
        // Validate input (you might want to add more validation)
        if (empty($username) || empty($password)) {
            return "Invalid input";
        }

        // Query the database (modify as needed)
        $query = "SELECT user_id, password FROM user_table WHERE username = '$username'";
        $result = $this->con->query($query);

        if ($result->num_rows > 0) {
            // Fetch user data
            $row = $result->fetch_assoc();
            $storedPasswordHash = $row['password'];

            // Verify password
            if (password_verify($password, $storedPasswordHash)) {
                // Password is correct

                // Start session
                session_start();

                // Set session variables
                $_SESSION['user_id'] = $row['user_id'];

                return "success";
            } else {
                // Password verification failed
                return "Invalid username or password";
            }
        } else {
            // User not found
            return "Invalid username or password";
        }
    }
}

// Instantiate LoginHandler
$loginHandler = new LoginHandler($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use the authenticateUser method
    echo $loginHandler->authenticateUser($username, $password);
} else {
    echo "Invalid request method";
}

// Close the database connection
$loginHandler->closeConnection();
?>
