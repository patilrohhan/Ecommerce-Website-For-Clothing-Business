<?php
// Admin/orders.php
require_once 'admin_functions.php';
requireLogin();

// Retrieve orders with customer information
$stmt = $pdo->query("SELECT o.id, o.total, o.status, c.fullname AS customer_name 
                     FROM orders o
                     JOIN users_info c ON o.user_id = c.id");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
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
      padding-top: 60px; /* Avoid overlap */
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

    /* Orders Table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #f4f4f4;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
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
    <h2>Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['id']); ?></td>
            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($order['total']); ?></td>
            <td><?php echo htmlspecialchars($order['status']); ?></td>
            <td>
              <a href="view_order.php?id=<?php echo $order['id']; ?>">View</a>
              <a href="update_order.php?id=<?php echo $order['id']; ?>">Update</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <footer>
      <p>&copy; <?php echo date("Y"); ?> SKYSHOP. All rights reserved.</p>
    </footer>
  </div>

  <!-- Inline JavaScript for Sidebar Toggle -->
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
