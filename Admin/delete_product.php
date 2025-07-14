<?php
// Admin/delete_product.php
require_once 'admin_functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Optional: fetch the product if you want to remove the old image file
// $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
// $stmt->execute([$id]);
// $product = $stmt->fetch(PDO::FETCH_ASSOC);
// if ($product && file_exists($product['image'])) {
//     unlink($product['image']);
// }

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: products.php");
exit();
