<?php
// create_order.php
header('Content-Type: application/json');

// Get the amount from the POSTed JSON data
$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'] ?? 0;

require 'vendor/autoload.php';

use Razorpay\Api\Api;

$apiKey = 'rzp_test_IexrrDEudvk0va';
$apiSecret = 'btYJfPjZUJhiVbES0x8Rm7N4';

$api = new Api($apiKey, $apiSecret);

$orderData = [
    'receipt'         => 'rcpt_' . time(),
    'amount'          => $amount, // Amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto-capture
];

try {
    $order = $api->order->create($orderData);
    echo json_encode($order);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
