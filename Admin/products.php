<?php
// Admin/products.php
require_once 'admin_functions.php';
requireLogin();

// Fetch products, including the 'stock' column
$stmt = $pdo->query("SELECT id, title, price, image, brand, stock, created_at FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
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
    /* Hide sidebar on small screens by default */
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
      background: #357ABD;
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
    .sidebar.hide + .main-content {
      margin-left: 0;
    }
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding-left: 60px; /* Extra padding prevents overlap from the toggle button */
      }
    }

    /* Products Table */
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
    .button {
      display: inline-block;
      padding: 10px 20px;
      background: #357ABD;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background 0.3s ease;
      margin-top: 10px;
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
      <li><a href="products.php" class="active">Products</a></li>
      <li><a href="orders.php">Orders</a></li>
      <li><a href="customers.php">Customers</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="main-content">
    <h2>Products</h2>
    <a href="add_product.php" class="button">Add New Product</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Image</th>
          <th>Brand</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?php echo htmlspecialchars($product['id']); ?></td>
            <td><?php echo htmlspecialchars($product['title']); ?></td>
            <td><?php echo htmlspecialchars($product['price']); ?></td>
            <td><?php echo htmlspecialchars($product['stock']); ?></td>
            <td>
              <?php if (!empty($product['image'])): ?>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width:80px;">
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($product['brand']); ?></td>
            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
            <td>
              <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
              <a href="delete_product.php?id=<?php echo $product['id']; ?>"
                 onclick="return confirm('Are you sure you want to delete this product?');">
                Delete
              </a>
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
