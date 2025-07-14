<?php 
// Admin/index.php
require_once 'admin_functions.php';
requireLogin();

// Get real counts from the database
$stmtProducts = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmtProducts->fetchColumn();

$stmtOrders = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $stmtOrders->fetchColumn();

$stmtCustomers = $pdo->query("SELECT COUNT(*) FROM users_info");
$totalCustomers = $stmtCustomers->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/script.js" defer></script>
  <style>
    /* Base styles */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    /* Sidebar Styling */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: linear-gradient(135deg, #4a90e2, #357ABD);
      color: white;
      padding-top: 20px;
      transition: transform 0.3s ease;
    }
    /* By default, hide sidebar on small screens */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.show {
        transform: translateX(0);
      }
    }
    /* For larger screens, allow collapsing if desired */
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
      background:linear-gradient(135deg, #4a90e2, #357ABD);
      color: white;
      border: none;
      padding: 10px;
      border-radius: 4px;
      cursor: pointer;
      z-index: 1000;
    }

    /* Main Content Styling */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }
    /* When sidebar is hidden (for larger screens) */
    .sidebar.hide + .main-content {
      margin-left: 0;
    }
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }

    /* Dashboard Cards */
    .dashboard {
      text-align: center;
      padding: 40px 20px;
    }
    .dashboard-cards {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 250px;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card h3 {
      margin-top: 0;
      color: #357ABD;
    }
    .card p {
      font-size: 1.5em;
      font-weight: 600;
      margin: 10px 0 0;
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
      <li><a href="index.php" class="active">Dashboard</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="orders.php">Orders</a></li>
      <li><a href="customers.php">Customers</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
  
  <!-- Main Content -->
  <div class="main-content" id="main-content">
    <div class="dashboard">
      <h2>Dashboard</h2>
      <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
      <p>From here, you can manage products, orders, customer info, and stock levels.</p>
      
      <!-- Dashboard Cards Section -->
      <div class="dashboard-cards">
        <div class="card">
          <h3>Total Products</h3>
          <p><?php echo $totalProducts; ?></p>
        </div>
        <div class="card">
          <h3>Total Orders</h3>
          <p><?php echo $totalOrders; ?></p>
        </div>
        <div class="card">
          <h3>Total Customers</h3>
          <p><?php echo $totalCustomers; ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Inline JavaScript for Sidebar Toggle -->
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {
      // For smaller screens, toggle the 'show' class.
      // For larger screens, you might want to toggle a 'hide' class.
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
      } else {
        sidebar.classList.toggle('hide');
      }
    });
  </script>
</body>
</html>
