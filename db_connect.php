<?php
// Database credentials
$servername = "localhost"; // Since you're running locally
$username   = "root";      // Default username for XAMPP
$password   = "";          // Default password for XAMPP (often empty)
$dbname     = "final project database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "";
?>
