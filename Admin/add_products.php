<?php
// Admin/add_product.php
require_once 'admin_functions.php';
requireLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $brand = trim($_POST['brand']);
    $stock = intval($_POST['stock']); // Convert stock to integer

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        // Generate a unique filename to avoid collisions
        $uniqueName = uniqid() . "_" . basename($_FILES['image']['name']);
        $imagePath = $targetDir . $uniqueName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }
    
    // Insert the product into the database
    // (created_at is handled automatically if your table sets a default)
    $stmt = $pdo->prepare("INSERT INTO products (title, price, image, brand, stock) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $price, $imagePath, $brand, $stock])) {
        header("Location: products.php");
        exit();
    } else {
        $error = "Failed to add product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Product</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>Admin Panel</h1>
    <nav>
      <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="orders.php">Orders</a></li>
        <li><a href="customers.php">Customers</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <h2>Add New Product</h2>
    <?php if ($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <label>Title:</label>
      <input type="text" name="title" required>
      <br><br>
      
      <label>Price:</label>
      <input type="number" name="price" step="0.01" required>
      <br><br>

      <label>Stock:</label>
      <input type="number" name="stock" required>
      <br><br>
      
      <label>Brand:</label>
      <input type="text" name="brand">
      <br><br>
      
      <label>Image:</label>
      <input type="file" name="image">
      <br><br>
      
      <button type="submit">Add Product</button>
    </form>
  </main>
  <footer>
    <p>&copy; <?php echo date("Y"); ?> SKYSHOP. All rights reserved.</p>
  </footer>
</body>
</html>
