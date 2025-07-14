<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}

$order_id = intval($_GET['order_id']);

// Fetch order details.
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$orderResult = $stmt->get_result();
if ($orderResult->num_rows === 0) {
    die("Order not found.");
}
$order = $orderResult->fetch_assoc();
$stmt->close();

// Fetch order items.
$stmt = $conn->prepare("SELECT order_items.quantity, order_items.price, products.title 
                        FROM order_items 
                        JOIN products ON order_items.product_id = products.id 
                        WHERE order_items.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$itemsResult = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - SkyShop</title>
  <script src="https://kit.fontawesome.com/00ea09fcaa.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    header {
      background: #088178;
      color: #fff;
      padding: 15px 30px;
      text-align: center;
    }

    #header h3{
      font-size: 30px;
    }

    #header .logo{
      top: -63%;
    }

    /* Cart Icon - Force white color like other pages */
    .cart-icon {
      font-size: 20px;
      color: #fff;  /* Parent link is white */
      text-decoration: none;
    }
    .cart-icon a i {
      margin-right: 6px;
      color: #1a1a1a !important; /* Overriding any other rule */
    }

    .container {
      max-width: 800px;
      margin: 30px auto;
      background: #fff;
      padding: 20px 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h2, h3 {
      color: #333;
    }
    .order-summary {
      margin-top: 20px;
    }
    .order-summary p {
      margin: 10px 0;
    }
    .order-items ul {
      list-style: none;
      padding: 0;
    }
    .order-items li {
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
      display: flex;
      justify-content: space-between;
    }
    .order-items li:last-child {
      border-bottom: none;
    }
    .back-to-home {
      display: inline-block;
      margin-top: 20px;
      background: #088178;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 3px;
    }
    .back-to-home:hover {
      background: #066a64;
    }
  </style>
</head>
<body>
  <!-- Header / Navbar -->
  <section id="header" class="fade-in">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <h3>Order Confirmed!</h3>
    <div class="cart-icon">
      <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
    </div>
  </section>
  <div class="container">
    <h2>Thank You for Your Order!</h2>
    <div class="order-summary">
      <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
      <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
      <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
      <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
    </div>
    <div class="order-items">
      <h3>Order Items:</h3>
      <ul>
        <?php while($item = $itemsResult->fetch_assoc()): ?>
          <li>
            <span><?php echo htmlspecialchars($item['title']); ?></span>
            <span>Qty: <?php echo $item['quantity']; ?></span>
            <span>$<?php echo number_format($item['price'], 2); ?></span>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
    <a class="back-to-home" href="index.php">Continue Shopping</a>
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

  <!-- Footer Section -->
  <footer class="section-p1">
    <div class="col">
      <img class="logo" src="img/skyshop.png" alt="">
      <h4>Contact</h4>
      <p><strong>Address: </strong>191 Ring Road, Street 18, Jalgaon, Maharashtra</p>
      <p><strong>Phone: </strong>+91 2224 1546 / +91 01 2546 5544</p>
      <p><strong>Hours: </strong>10:00 - 18:00, Mon - Sat</p>
      <div class="follow">
        <h4>Follow Us</h4>
        <div class="icons">
          <i class="fa-brands fa-facebook"></i>
          <i class="fa-brands fa-instagram"></i>
          <i class="fa-brands fa-x-twitter"></i>
          <i class="fa-brands fa-youtube"></i>
        </div>
      </div>
    </div>
    <div class="col">
      <h4>About</h4>
      <a href="#">About Us</a>
      <a href="#">Delivery Information</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms & Conditions</a>
      <a href="#">Contact Us</a>
    </div>
    <div class="col">
      <h4>My Account</h4>
      <a href="#">Sign In</a>
      <a href="#">View Cart</a>
      <a href="#">My Wishlist</a>
      <a href="#">Track My Order</a>
      <a href="#">Help</a>
    </div>
    <div class="col install">
      <h4>Install App</h4>
      <p>From App Store or Google Play</p>
      <div class="row">
        <a href="#"><img src="img/Pay/app.jpg" alt=""></a>
        <a href="#"><img src="img/Pay/play.jpg" alt=""></a>
      </div>
      <p>Secured Payment Gateways</p>
      <img src="img/Pay/pay.png" alt="">
    </div>
  </footer>
</body>
</html>