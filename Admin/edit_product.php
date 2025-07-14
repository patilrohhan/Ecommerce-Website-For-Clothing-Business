<?php
// Admin/edit_product.php
require_once 'admin_functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);
$error = '';

// Fetch existing product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: products.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $brand = trim($_POST['brand']);
    $stock = intval($_POST['stock']);

    // Keep existing image unless a new one is uploaded
    $imagePath = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $uniqueName = uniqid() . "_" . basename($_FILES['image']['name']);
        $imagePath = $targetDir . $uniqueName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $updateStmt = $pdo->prepare("
        UPDATE products 
        SET title = ?, price = ?, image = ?, brand = ?, stock = ?
        WHERE id = ?
    ");
    if ($updateStmt->execute([$title, $price, $imagePath, $brand, $stock, $id])) {
        header("Location: products.php");
        exit();
    } else {
        $error = "Failed to update product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/script.js" defer></script>
  <style>
    body { margin:0; font-family:Arial,sans-serif; }

    /* Sidebar */
    .sidebar {
      width:250px; height:100vh; position:fixed;
      background:linear-gradient(135deg,#4a90e2,#357ABD);
      color:#fff; padding-top:20px; transition:transform .3s ease;
    }
    @media(max-width:768px){
      .sidebar { transform:translateX(-100%); }
      .sidebar.show { transform:translateX(0); }
    }
    .sidebar.hide { transform:translateX(-100%); }
    .sidebar h2 { text-align:center; margin-bottom:20px; }
    .sidebar ul { list-style:none; margin:0; padding:0; }
    .sidebar ul li { padding:15px; }
    .sidebar ul li a {
      color:#fff; text-decoration:none; font-size:1.2em;
      display:block; padding:10px 15px; border-radius:4px;
      transition:background .3s,padding-left .3s;
    }
    .sidebar ul li a:hover { background:rgba(255,255,255,.2); padding-left:25px; }
    .sidebar ul li a.active { background:rgba(255,255,255,.3); }

    /* Toggle Button */
    .menu-toggle {
      position:fixed; top:15px; left:15px;
      background:#357ABD; color:#fff; border:none;
      padding:10px; border-radius:4px; cursor:pointer; z-index:1000;
    }

    /* Main Content */
    .main-content {
      margin-left:250px; padding:20px; padding-top:60px;
      transition:margin-left .3s ease;
    }
    .sidebar.hide + .main-content { margin-left:0; }
    @media(max-width:768px){ .main-content { margin-left:0; } }

    /* Edit Form Card */
    .edit-form {
      max-width:600px; margin:0 auto;
      background:#fff; padding:20px; border-radius:8px;
      box-shadow:0 2px 8px rgba(0,0,0,.1);
    }
    .edit-form h2 { color:#357ABD; margin-top:0; }
    .edit-form label { display:block; margin:15px 0 5px; font-weight:600; }
    .edit-form input[type="text"],
    .edit-form input[type="number"],
    .edit-form input[type="file"] {
      width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;
    }
    .edit-form button {
      margin-top:20px; padding:10px 20px;
      background:#357ABD; color:#fff; border:none; border-radius:4px;
      cursor:pointer; transition:background .3s ease;
    }
    .edit-form button:hover { background:#4a90e2; }
    .error { color:#e74c3c; text-align:center; margin-bottom:15px; }
    .current-image { margin:10px 0; }
    .current-image img { max-width:100px; display:block; }
  </style>
</head>
<body>
  <!-- Toggle -->
  <button id="menu-toggle" class="menu-toggle">&#9776;</button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="products.php" class="active">Products</a></li>
      <li><a href="orders.php">Orders</a></li>
      <li><a href="customers.php">Customers</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main -->
  <div class="main-content" id="main-content">
    <div class="edit-form">
      <h2>Edit Product</h2>
      <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
      <?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>

        <label>Price</label>
        <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label>Stock</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

        <label>Brand</label>
        <input type="text" name="brand" value="<?= htmlspecialchars($product['brand']) ?>">

        <label class="current-image">Current Image</label>
        <?php if (!empty($product['image'])): ?>
          <img src="<?= htmlspecialchars($product['image']) ?>" alt="" class="current-image">
        <?php else: ?>
          <p>No image</p>
        <?php endif; ?>

        <label>New Image (optional)</label>
        <input type="file" name="image">

        <button type="submit">Update Product</button>
      </form>
    </div>
    <footer>&copy; <?= date("Y") ?> SKYSHOP. All rights reserved.</footer>
  </div>

  <!-- Toggle Script -->
  <script>
    const menuToggle = document.getElementById('menu-toggle'),
          sidebar    = document.getElementById('sidebar');
    menuToggle.addEventListener('click', () => {
      if (window.innerWidth <= 768) sidebar.classList.toggle('show');
      else sidebar.classList.toggle('hide');
    });
  </script>
</body>
</html>
