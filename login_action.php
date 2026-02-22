<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php'; // Your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user details
    $query = "SELECT * FROM users WHERE username = ?"; // Change 'email' to 'username'
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password using bcrypt
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];  // Role: 'admin' or 'cashier'
            $_SESSION['username'] = $user['username']; // Store the username in the session

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] == 'cashier') {
                header('Location: cashier_dashboard.php');
            }
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password.";
        }
    } else {
        // User not found
        $error_message = "Invalid username or password.";
    }
}
?>
