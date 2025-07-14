<?php
// Admin/update_order.php
require_once 'admin_functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Fetch the existing order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($updateStmt->execute([$new_status, $order_id])) {
        header("Location: view_order.php?id=" . $order_id);
        exit();
    } else {
        $error = "Failed to update order status.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Order</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/script.js" defer></script>
  <style>
    /* Base */
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

    /* Toggle */
    .menu-toggle {
      position:fixed; top:15px; left:15px;
      background:#357ABD; color:#fff; border:none;
      padding:10px; border-radius:4px; cursor:pointer; z-index:1000;
    }

    /* Main */
    .main-content {
      margin-left:250px; padding:20px; padding-top:60px;
      transition:margin-left .3s ease;
    }
    .sidebar.hide + .main-content { margin-left:0; }
    @media(max-width:768px){ .main-content { margin-left:0; } }

    /* Update Form */
    .update-form {
      max-width:500px; margin:0 auto;
      background:#fff; padding:20px; border-radius:8px;
      box-shadow:0 2px 8px rgba(0,0,0,.1);
    }
    .update-form label { display:block; margin-bottom:8px; font-weight:600; }
    .update-form select {
      width:100%; padding:10px; margin-bottom:15px;
      border:1px solid #ddd; border-radius:4px;
    }
    .update-form button {
      padding:10px 20px; background:#4a90e2; color:#fff;
      border:none; border-radius:4px; cursor:pointer;
      transition:background .3s ease;
    }
    .update-form button:hover { background:#357ABD; }
    .error { color:#e74c3c; margin-bottom:15px; text-align:center; }

    /* Footer */
    footer {
      text-align:center; padding:20px; background:#fff;
      border-top:1px solid #ddd; font-size:.9em; color:#777;
      margin-top:20px;
    }
  </style>
</head>
<body>
  <!-- Toggle Button -->
  <button id="menu-toggle" class="menu-toggle">&#9776;</button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="orders.php" class="active">Orders</a></li>
      <li><a href="customers.php">Customers</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="main-content">
    <h2 style="text-align:center;">Update Order</h2>
    <?php if ($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <div class="update-form">
      <form method="POST">
        <label for="status">Order Status:</label>
        <select name="status" id="status" required>
          <?php foreach (['Pending','Processing','Shipped','Delivered','Cancelled'] as $st): ?>
            <option value="<?= $st ?>"
              <?= $order['status']==$st ? 'selected' : '' ?>>
              <?= $st ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit">Update Order</button>
      </form>
    </div>
    <footer>&copy; <?php echo date("Y"); ?> SKYSHOP. All rights reserved.</footer>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    const menuToggle = document.getElementById('menu-toggle'),
          sidebar = document.getElementById('sidebar');
    menuToggle.addEventListener('click', () => {
      if (window.innerWidth <= 768) sidebar.classList.toggle('show');
      else sidebar.classList.toggle('hide');
    });
  </script>
</body>
</html>
