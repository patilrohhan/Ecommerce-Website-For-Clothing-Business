<?php 
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: signin.php");
  exit;
}
$fullname = $_SESSION['fullname'] ?? "";
$user_name = $_SESSION['username'] ?? "";
$email = $_SESSION['email'] ?? "";
$user_id = $_SESSION['user_id'];

// Fetch orders for this user and group them by order ID
include 'db_connect.php';
$stmt = $conn->prepare("
  SELECT 
    orders.id AS order_id, 
    orders.order_date, 
    orders.total, 
    orders.status, 
    products.image, 
    products.title 
  FROM orders 
  LEFT JOIN order_items ON orders.id = order_items.order_id 
  LEFT JOIN products ON order_items.product_id = products.id 
  WHERE orders.user_id = ? 
  ORDER BY orders.order_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
  $order_id = $row['order_id'];
  if (!isset($orders[$order_id])) {
    $orders[$order_id] = [
      'order_date' => $row['order_date'],
      'total'      => $row['total'],
      'status'     => $row['status'],
      'items'      => []
    ];
  }
  // If the order item has product details, add it to the order
  if (!empty($row['image']) && !empty($row['title'])) {
    $orders[$order_id]['items'][] = [
      'image' => $row['image'],
      'title' => $row['title']
    ];
  }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile - SkyShop</title>
  <link rel="stylesheet" href="style.css">
  <!-- Include FontAwesome Kit -->
  <script src="https://kit.fontawesome.com/00ea09fcaa.js" crossorigin="anonymous"></script>
  <style>
    /* --- Profile Page Custom Styles --- */
    .profile-container {
      max-width: 900px;
      margin: 30px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .profile-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
      padding-bottom: 20px;
      margin-bottom: 20px;
    }
    .profile-info {
      display: flex;
      align-items: center;
    }
    .profile-pic {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background: #088178;
      color: #fff;
      font-size: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 20px;
      border: 2px solid #fff;
    }
    .profile-details h2 {
      margin: -12px 0;
      font-size: 28px;
      color: #777;
    }
    .profile-details p {
      margin: -8px 0;
      color: #777;
      font-size: 16px;
    }
    .logout-btn {
      background: #e74c3c;
      color: #fff;
      border: none;
      padding: 12px 24px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px;
      transition: background 0.3s ease;
    }
    .logout-btn:hover {
      background: #c0392b;
    }
    /* --- Orders Section --- */
    .orders-section {
      margin-top: 30px;
    }
    .orders-section h3 {
      margin-bottom: 20px;
      font-size: 22px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
      color: #333;
    }
    .order-card {
      border: 1px solid #eee;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      transition: box-shadow 0.3s ease;
    }
    .order-card:hover {
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .order-card h4 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #088178;
    }
    .order-details {
      font-size: 14px;
      color: #555;
      line-height: 1.6;
    }
    .order-items {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }
    .order-item {
      width: 80px;
      text-align: center;
    }
    .order-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-bottom: 5px;
    }
    .order-item span {
      font-size: 12px;
      color: #333;
    }
    /* --- Newsletter Section --- */
    .newsletter {
      background: #f9f9f9;
      padding: 30px;
      text-align: center;
      margin: 40px auto 0;
      border-radius: 10px;
      max-width: 800px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .newsletter h4 {
      margin-bottom: 10px;
      font-size: 24px;
      color: #333;
    }
    .newsletter p {
      margin-bottom: 20px;
      font-size: 16px;
      color: #666;
    }
    .newsletter input[type="text"] {
      padding: 10px;
      width: 60%;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-right: 10px;
      font-size: 16px;
    }
    .newsletter button {
      padding: 10px 20px;
      border: none;
      background: #088178;
      color: #fff;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .newsletter button:hover {
      background: #066a60;
    }
  </style>
</head>
<body>
  <!-- Header Section (same as index.php) -->
  <section id="header" class="fade-in">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <div>
      <ul id="navbar">
        <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if(isset($_SESSION['username'])): ?>
          <li id="lg-bag">
            <a href="cart.php">
              <i class="fa-solid fa-bag-shopping"></i>
              <span id="cart-count" style="display: none;">0</span>
            </a>
          </li>
        <?php endif; ?>
        <li id="search-btn">
          <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
        </li>
        <?php if(isset($_SESSION['username'])): ?>
          <li id="user-login">
            <a class="active" href="profile.php"><i class="fa-solid fa-user"></i></a>
          </li>
        <?php else: ?>
          <li id="user-login">
            <a href="signin.php"><i class="fa-solid fa-user-plus"></i></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
    <div id="mobile">
      <?php if(isset($_SESSION['username'])): ?>
        <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
      <?php endif; ?>
      <i id="bar" class="fa-solid fa-bars"></i>
    </div>
  </section>

  <!-- Profile Container -->
  <div class="profile-container fade-in">
    <div class="profile-header">
      <div class="profile-info">
        <div class="profile-pic">
          <?php echo strtoupper(substr($fullname, 0, 1)); ?>
        </div>
        <div class="profile-details">
          <h2><?php echo htmlspecialchars($fullname); ?></h2>
          <p>@<?php echo htmlspecialchars($user_name); ?></p>
        </div>
      </div>
      <!-- Logout Button -->
      <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>

    <!-- Orders Section -->
    <div class="orders-section">
      <h3>Orders</h3>
      <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order_id => $order): ?>
          <div class="order-card">
            <h4>Order <?php echo htmlspecialchars($order_id); ?></h4>
            <div class="order-details">
              <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
              <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
              <div class="order-items">
                <?php if (count($order['items']) > 0): ?>
                  <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                      <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                      <span><?php echo htmlspecialchars($item['title']); ?></span>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>No items found.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No orders found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Newsletter Section with Fade-In -->
  <section id="newsletter" class="section-p1 section-m1 fade-in">
    <div class="newstext">
      <h4>Sign Up For newsletters</h4>
      <p>Get E-mail updates about our latest stock and <span>special offers.</span></p>
    </div>
    <div class="form">
      <input type="text" placeholder="Your email address">
      <button class="normal">Sign Up</button>
    </div>
  </section>

  <!-- Footer Section (identical markup as in your other pages) -->
  <footer class="section-p1 fade-in">
    <div class="col">
      <img class="logo" src="img/skyshop.png" alt="">
      <h4>Contact</h4>
      <p><strong>Address: </strong>191 Ring Road, Street 18, Jalgaon, Maharashtra</p>
      <p><strong>Phone: </strong>+91 2224 1546 / +91 01 2546 5544</p>
      <p><strong>Hours: </strong>10:00 - 18:00, Mon - Sat</p>
      <div class="follow">
        <h4>Follow Us</h4>
        <div class="icons">
          <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook"></i></a>
          <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
          <a href="https://x.com/?lang=en-in"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="https://www.youtube.com/"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="col">
      <h4>About</h4>
      <a href="about.html">About Us</a>
      <a href="#">Delivery Information</a>
      <a href="about.html">Privacy Policy</a>
      <a href="about.html">Terms & Conditions</a>
      <a href="contact.html">Contact Us</a>
    </div>
    <div class="col">
      <h4>My Account</h4>
      <a href="signin.html">Sign In</a>
      <a href="cart.html">View Cart</a>
      <a href="cart.html">My Wishlist</a>
      <a href="cart.html">Track My Order</a>
      <a href="contact.html">Help</a>
    </div>
    <div class="col install">
      <h4>Install App</h4>
      <p>From App Store or Google Play</p>
      <div class="row">
        <a href="https://www.apple.com/app-store/"><img src="img/Pay/app.jpg" alt=""></a>
        <a href="https://play.google.com/store/games?device=windows&pli=1"><img src="img/Pay/play.jpg" alt=""></a>
      </div>
      <p>Secured Payment Gateways</p>
      <img src="img/Pay/pay.png" alt="">
    </div>
  </footer>
</body>
</html>
