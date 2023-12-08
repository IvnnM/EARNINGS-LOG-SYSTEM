<?php

// Include the DatabaseHandler class for inheritance
include_once __DIR__ . '/../connection.php';

// LoginHandler class inherits from DatabaseHandler class
class LoginHandler extends DatabaseHandler {

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

            // Verify password using PHP's password_verify function
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
$loginHandler = new LoginHandler();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use the authenticateUser method
    echo $loginHandler->authenticateUser($username, $password);
} else {
    // If the request method is not POST, return an error message
    echo "Invalid request method";
}

?>
