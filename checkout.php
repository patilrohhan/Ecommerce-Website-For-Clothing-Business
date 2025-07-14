<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? 1; // Example fallback user ID

// Fetch cart items from DB
$stmt = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.title, products.price, products.image 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}
$stmt->close();

// Start with empty address lines (no preloaded text)
$address_line1 = "";
$address_line2 = "";
$address_line3 = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Secure Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/00ea09fcaa.js" crossorigin="anonymous"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


  <!-- Google Font for a modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    /* Basic Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f5f5f7;
      color: #111;
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
      color: #fff;
      text-decoration: none;
    }
    .cart-icon a i {
      margin-right: 6px;
      color: #1a1a1a !important;
    }
    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 20px auto;
      display: flex;
      gap: 20px;
      padding: 0 10px;
    }
    /* Left Column: Address & Payment Form */
    .left-col {
      flex: 2;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
    }
    .address-section, .payment-section {
      margin-bottom: 30px;
    }
    .address-section h3,
    .payment-section h3 {
      font-size: 18px;
      margin-bottom: 10px;
    }
    .address-section input {
      display: block;
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    /* Payment Options */
    .payment-option {
      margin-bottom: 15px;
    }
    .payment-option label {
      cursor: pointer;
      font-weight: 500;
      display: flex;
      align-items: center;
    }
    .payment-option input[type="radio"] {
      margin-right: 8px;
    }
    .payment-details {
      display: none;
      background-color: #f8f8f8;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 4px;
      margin-top: 8px;
    }
    .payment-details input,
    .payment-details select {
      display: block;
      width: 100%;
      padding: 8px;
      margin-bottom: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    /* Styling for the UPI Verify button */
    #verify-upi-btn {
      background-color: #088178;
      border: none;
      color: #fff;
      padding: 8px 12px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 10px;
      transition: background-color 0.3s ease;
    }
    #verify-upi-btn:hover {
      background-color: #066a60;
    }
    /* Right Column: Order Summary */
    .right-col {
      flex: 1;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      height: fit-content;
      position: sticky;
      top: 20px;
      align-self: flex-start;
    }
    .order-summary h3 {
      font-size: 18px;
      margin-bottom: 10px;
    }
    .order-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .order-item img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      margin-right: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .order-item .title {
      font-size: 14px;
      font-weight: 500;
    }
    .order-item .quantity {
      font-size: 13px;
      color: #555;
    }
    .order-total {
      margin-top: 10px;
      font-size: 16px;
      font-weight: 700;
      text-align: right;
    }
    /* Place Order Button */
    .place-order-btn {
      display: inline-block;
      background-color: #f0c14b;
      border: 1px solid #a88734;
      padding: 12px 20px;
      font-size: 16px;
      font-weight: 700;
      color: #111;
      border-radius: 4px;
      text-align: center;
      margin-top: 20px;
      cursor: pointer;
      width: 100%;
      text-decoration: none;
    }
    .place-order-btn:hover {
      background-color: #ddb347;
    }
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .right-col {
        position: static;
        top: auto;
        margin-top: 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Header / Navbar -->
  <section id="header" class="fade-in">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <h3>Secure Checkout</h3>
    <div class="cart-icon">
      <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
    </div>
  </section>

  <!-- Checkout Form -->
  <form id="checkout-form" action="place_order.php" method="POST">
    <div class="container">
      <!-- Left Column: Address & Payment -->
      <div class="left-col">
        <!-- Address Section (no preloaded text) -->
        <div class="address-section">
          <h3>Shipping Address</h3>
          <input type="text" name="address_line1" placeholder="Address Line 1" required>
          <input type="text" name="address_line2" placeholder="Address Line 2">
          <input type="text" name="address_line3" placeholder="Address Line 3">
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
          <h3>Payment Mode</h3>

          <!-- Credit/Debit Card -->
          <div class="payment-option">
            <label>
              <input type="radio" name="payment_method" value="Card">
              Credit/Debit Card
            </label>
            <div class="payment-details card-details">
              <input type="text" name="card_holder" placeholder="Cardholder Name">
              <select name="card_type">
                <option value="">Select Card Type</option>
                <option value="visa">Visa</option>
                <option value="mastercard">MasterCard</option>
                <option value="amex">American Express</option>
              </select>
              <input type="text" name="card_number" placeholder="Card Number">
              <input type="text" name="card_expiry" placeholder="Expiry Date (MM/YY)">
              <input type="text" name="card_cvv" placeholder="CVV">
            </div>
          </div>

          <!-- Net Banking -->
          <div class="payment-option">
            <label>
              <input type="radio" name="payment_method" value="Net Banking">
              Net Banking
            </label>
            <div class="payment-details netbanking-details">
              <select name="bank_name">
                <option value="">Select Bank</option>
                <option value="HDFC">HDFC Bank</option>
                <option value="SBI">SBI Bank</option>
                <option value="ICICI">ICICI Bank</option>
              </select>
              <input type="text" name="netbanking_userid" placeholder="Enter your Net Banking User ID">
            </div>
          </div>

          <!-- UPI Payment Option -->
          <div class="payment-option">
            <label>
              <input type="radio" name="payment_method" value="UPI">
              UPI
            </label>
            <div class="payment-details upi-details">
              <input type="text" name="upi_id" id="upi_id" placeholder="Enter UPI ID (e.g., name@bank)">
              <button type="button" id="verify-upi-btn">Verify</button>
              <small id="upi-msg" style="color: #555; font-size: 12px; display: block; margin-top: 5px;">
                Format: username@bank
              </small>
            </div>
          </div>

          <!-- Cash on Delivery -->
          <div class="payment-option">
            <label>
              <input type="radio" name="payment_method" value="COD">
              Cash on Delivery
            </label>
            <div class="payment-details cod-details">
              <p style="font-size: 14px; margin: 0; color: #555;">
                Pay with cash/UPI when your order is delivered.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Order Summary -->
      <div class="right-col">
        <div class="order-summary">
          <h3>Order Summary</h3>
          <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
              <div class="order-item">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div>
                  <div class="title"><?php echo htmlspecialchars($item['title']); ?></div>
                  <div class="quantity">Qty: <?php echo $item['quantity']; ?></div>
                  <div class="price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="order-total">
              Order Total: $<?php echo number_format($total, 2); ?>
            </div>
            <!-- Hidden field to pass total to place_order.php -->
            <input type="hidden" name="order_total" value="<?php echo $total; ?>">
          <?php else: ?>
            <p>Your cart is empty.</p>
          <?php endif; ?>
        </div>
        <!-- Hidden fields to capture Razorpay payment details -->
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        <!-- Change the button type to button and add an ID -->
        <button type="button" id="place-order-btn" class="place-order-btn">Use this payment method</button>
      </div>
    </div>
  </form>

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

  <!-- JavaScript to Show/Hide Payment Details -->
  <script>
    document.getElementById('verify-upi-btn').addEventListener('click', function() {
      var upiInput = document.getElementById('upi_id');
      var upiId = upiInput.value.trim();
      var msgEl = document.getElementById('upi-msg');
      // Simple regex for validating UPI ID format
      var regex = /^\S+@\S+$/;
      if (upiId === '') {
        msgEl.textContent = 'Please enter a UPI ID.';
        msgEl.style.color = 'red';
      } else if (!regex.test(upiId)) {
        msgEl.textContent = 'Invalid UPI ID format. Please use format: username@bank';
        msgEl.style.color = 'red';
      } else {
        // Simulate verification
        msgEl.textContent = 'UPI ID verified!';
        msgEl.style.color = 'green';
      }
    });

    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const allDetails = document.querySelectorAll('.payment-details');

    paymentRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        // Hide all detail sections
        allDetails.forEach(div => { div.style.display = 'none'; });
        // Show the relevant detail section based on the value (ensure matching case)
        if (radio.value === 'Card') {
          document.querySelector('.card-details').style.display = 'block';
        } else if (radio.value === 'Net Banking') {
          document.querySelector('.netbanking-details').style.display = 'block';
        } else if (radio.value === 'UPI') {
          document.querySelector('.upi-details').style.display = 'block';
        } else if (radio.value === 'COD') {
          document.querySelector('.cod-details').style.display = 'block';
        }
      });
    });
  </script>

  <!-- JavaScript: Handle Place Order and Razorpay Integration -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById("place-order-btn").addEventListener("click", function(e) {
        e.preventDefault();
        var selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if(selectedMethod === "COD"){
          // For Cash on Delivery, submit the form directly.
          document.getElementById("checkout-form").submit();
        } else {
          // For online payments, use Razorpay.
          var total = <?php echo $total; ?>;
          var amount = total * 100; // convert to paise
          
          // Create an order via your backend (create_order.php should return a JSON with order id)
          fetch("create_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ amount: amount })
          })
          .then(response => response.json())
          .then(orderData => {
            if(orderData.error){
              console.error("Order creation error:", orderData.error);
              return;
            }
            var options = {
              "key": "rzp_test_IexrrDEudvk0va", // Replace with your Razorpay Key ID
              "amount": amount,
              "currency": "INR",
              "name": "SkyShop",
              "description": "Order Payment",
              "order_id": orderData.id,
              "handler": function (response){
                document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;
                document.getElementById("razorpay_order_id").value = response.razorpay_order_id;
                document.getElementById("razorpay_signature").value = response.razorpay_signature;
                document.getElementById("checkout-form").submit();
              },
              "prefill": {
                "name": "<?php echo $_SESSION['username'] ?? 'Guest'; ?>",
                "email": "user@example.com",
                "contact": "9876543210"
              },
              "theme": {
                "color": "#F37254"
              }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
          })
          .catch(error => {
            console.error("Error creating Razorpay order:", error);
          });
        }
      });
    });
  </script>
</body>
</html>
