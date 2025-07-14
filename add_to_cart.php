<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in by checking the username
if (!isset($_SESSION['username'])) {
    echo "Please log in to add items to your cart.";
    exit;
}

$username = $_SESSION['username'];

// If user_id is not set, query the database to get it
if (!isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id FROM users_info WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "User not found.";
        exit;
    }
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    // Optionally, store the user_id in session for later use
    $_SESSION['user_id'] = $user_id;
    $stmt->close();
} else {
    $user_id = $_SESSION['user_id'];
}

$product_id    = $_POST['product_id'];
$product_title = $_POST['product_title'];
$product_image = $_POST['product_image'];
$product_price = $_POST['product_price']; // If you wish to store the price in the cart table
$quantity      = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Insert product into cart table
$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE quantity = quantity + ?");
$stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);

if ($stmt->execute()) {
    echo "Item added to cart.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
