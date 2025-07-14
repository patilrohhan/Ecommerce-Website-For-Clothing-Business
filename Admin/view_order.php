<?php
// Admin/view_order.php
require_once 'admin_functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Fetch order details and join with customer details
$stmt = $pdo->prepare("SELECT o.*, c.fullname, c.email, o.order_date 
                       FROM orders o 
                       JOIN users_info c ON o.user_id = c.id 
                       WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Order</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/script.js" defer></script>
  <style>
    /* Base styles */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: linear-gradient(135deg, #4a90e2, #357ABD);
      color: white;
      padding-top: 20px;
      transition: transform 0.3s ease;
    }
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.show {
        transform: translateX(0);
      }
    }
    .sidebar.hide {
      transform: translateX(-100%);
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li {
      padding: 15px;
      text-align: left;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 1.2em;
      display: block;
      padding: 10px 15px;
      border-radius: 4px;
      transition: background 0.3s, padding-left 0.3s;
    }
    .sidebar ul li a:hover {
      background: rgba(255, 255, 255, 0.2);
      padding-left: 25px;
    }
    .sidebar ul li a.active {
      background: rgba(255, 255, 255, 0.3);
    }

    /* Menu Toggle Button */
    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      background: #357ABD;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 4px;
      cursor: pointer;
      z-index: 1000;
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      padding-top: 60px; /* avoid overlap with toggle */
      transition: margin-left 0.3s ease;
    }
    .sidebar.hide + .main-content {
      margin-left: 0;
    }
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }

    /* Order Details Card */
    .order-details {
      max-width: 600px;
      margin: 0 auto 20px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .order-details h2 {
      margin-top: 0;
      color: #357ABD;
    }
    .order-details p {
      margin: 10px 0;
    }
    .order-details h3 {
      margin-top: 20px;
      color: #357ABD;
    }

    /* Button */
    .button {
      display: inline-block;
      padding: 10px 20px;
      background: #357ABD;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background 0.3s ease;
    }
    .button:hover {
      background: #4a90e2;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 20px;
      background-color: #fff;
      border-top: 1px solid #ddd;
      font-size: 0.9em;
      color: #777;
      margin-top: 20px;
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
    <div class="order-details">
      <h2>Order Details</h2>
      <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
      <p><strong>Total:</strong> <?php echo htmlspecialchars($order['total']); ?></p>
      <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
      <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
      <hr>
      <h3>Customer Details</h3>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
    </div>
    <div style="text-align: center; margin-bottom: 20px;">
      <a href="update_order.php?id=<?php echo $order['id']; ?>" class="button">Update Order</a>
    </div>

    <footer>
      <p>&copy; <?php echo date("Y"); ?> SKYSHOP. All rights reserved.</p>
    </footer>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    menuToggle.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
      } else {
        sidebar.classList.toggle('hide');
      }
    });
  </script>
</body>
</html>
