<?php
session_start();
// Start output buffering and enable error reporting for debugging
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a session to store user data


// Include the database connection file (ensure this file does not output anything)
include 'db_connect.php';

// Process the form submission only if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize form data
    $fullname         = trim($_POST['fullname']);
    $username         = trim($_POST['username']);
    $email            = trim($_POST['email']);
    $password         = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic server-side validation
    if (empty($fullname) ||  empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit;
    }

    // Check if the user already exists (using email as the unique identifier)
    $stmt = $conn->prepare("SELECT email FROM users_info WHERE email = ? LIMIT 1");
    if (!$stmt) {
        echo "Database error: " . $conn->error;
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // A record with this email exists; redirect with an error flag
        $stmt->close();
        header("Location: signup.php?error=userexists");
        exit;
    }
    
    $stmt->close();

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the query to insert the new user record
    $stmt = $conn->prepare("INSERT INTO users_info (fullname, username, email, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "Database error: " . $conn->error;
        exit;
    }
    $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

    // Execute the insert query and check if registration is successful
    if ($stmt->execute()) {
        // Registration successful; get the new user's ID
        $user_id = $conn->insert_id;

        // Set session variables to keep the user logged in
        $_SESSION['fullname'] = $fullname;
        $_SESSION['user_id']  = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email']    = $email;

        // Redirect the user to the index.php page
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // If the form wasn't submitted via POST, display an error message.
    echo "Invalid request method.";
}

ob_end_flush();
?>
