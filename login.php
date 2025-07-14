<?php
// login.php

session_start();
include 'db_connect.php';

// Process the form submission if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation: Ensure fields are not empty
    if (empty($email) || empty($password)) {
        header("Location: signin.php?error=emptyfields");
        exit;
    }

    // ----------- Check if user is an Admin -----------
    $stmt = $conn->prepare("SELECT id, username, email, password FROM admins WHERE email = ? LIMIT 1");
    if (!$stmt) {
        header("Location: signin.php?error=sqlerror");
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult && $adminResult->num_rows === 1) {
        $adminUser = $adminResult->fetch_assoc();
        // Verify the entered password with the hashed password from the database
        if (password_verify($password, $adminUser['password'])) {
            // Admin credentials are correct, set session variables for admin
            $_SESSION['fullname'] = $adminUser['fullname'];
            $_SESSION['user_id']  = $adminUser['id'];
            $_SESSION['username'] = $adminUser['username'];
            $_SESSION['email']    = $adminUser['email'];
            $_SESSION['role']     = 'admin'; // Mark as admin
            header("Location: index.php"); // Redirect to admin dashboard
            exit;
        } else {
            // Incorrect password for admin account
            header("Location: signin.php?error=wrongpassword");
            exit;
        }
    }
    $stmt->close();

    // ----------- Check in Regular Users Table -----------
    $stmt = $conn->prepare("SELECT id, fullname, username, email, password FROM users_info WHERE email = ? LIMIT 1");
    if (!$stmt) {
        header("Location: signin.php?error=sqlerror");
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult && $userResult->num_rows === 1) {
        $user = $userResult->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Credentials are correct, set session variables for regular user
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = 'user'; // Mark as regular user
            header("Location: index.php"); // Redirect to home page or user dashboard
            exit;
        } else {
            // Incorrect password for regular user
            header("Location: signin.php?error=wrongpassword");
            exit;
        }
    } else {
        // No user found in either table with that email address
        header("Location: signin.php?error=nouser");
        exit;
    }
    $stmt->close();
} else {
    // If not a POST request, redirect back to the sign in page.
    header("Location: signin.php");
    exit;
}
?>
