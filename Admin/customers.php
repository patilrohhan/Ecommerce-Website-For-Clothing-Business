<?php
// Admin/customers.php
require_once 'admin_functions.php';
requireLogin();

// Retrieve customers from the database
$stmt = $pdo->query("SELECT * FROM users_info");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Customers</title>
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
      padding-top: 60px; /* Avoid overlap with toggle */
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

    /* Customers Table */
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
      <li><a href="orders.php">Orders</a></li>
      <li><a href="customers.php" class="active">Customers</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="main-content">
    <h2>Customers</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Created At</th>
          <th>Orders</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $customer): ?>
          <tr>
            <td><?php echo htmlspecialchars($customer['id']); ?></td>
            <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
            <td><?php echo htmlspecialchars($customer['username']); ?></td>
            <td><?php echo htmlspecialchars($customer['email']); ?></td>
            <td><?php echo htmlspecialchars($customer['created_at']); ?></td>
            <td>
              <?php 
              $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
              $stmtCount->execute([$customer['id']]);
              echo $stmtCount->fetchColumn();
              ?>
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
