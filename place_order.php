<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = trim($_POST['payment_method']);
$address_line1 = trim($_POST['address_line1'] ?? '');
$address_line2 = trim($_POST['address_line2'] ?? '');
$address_line3 = trim($_POST['address_line3'] ?? '');

// Combine the address lines into one shipping address (adjust separator as needed)
$shipping_address = $address_line1;
if (!empty($address_line2)) {
    $shipping_address .= "\n" . $address_line2;
}
if (!empty($address_line3)) {
    $shipping_address .= "\n" . $address_line3;
}

$payment_method = trim($_POST['payment_method'] ?? '');

if (empty($shipping_address) || empty($payment_method)) {
    die("Shipping address and payment method are required.");
}

// Fetch cart items.
$stmt = $conn->prepare("SELECT cart.product_id, cart.quantity, products.price 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}
$stmt->close();

if (count($cartItems) === 0) {
    die("Your cart is empty.");
}

// Start transaction.
$conn->begin_transaction();

// Insert the order.
$stmt = $conn->prepare("INSERT INTO orders (user_id, total, shipping_address, payment_method, order_date) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("idss", $user_id, $total, $shipping_address, $payment_method);
if (!$stmt->execute()) {
    $conn->rollback();
    die("Failed to create order.");
}
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each order item.
foreach ($cartItems as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    if (!$stmt->execute()) {
        $conn->rollback();
        die("Failed to add order items.");
    }
    $stmt->close();
}

// Clear the cart.
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Commit the transaction.
$conn->commit();

// Redirect to the order confirmation page.
header("Location: order_confirmation.php?order_id=" . $order_id);
exit;
?>