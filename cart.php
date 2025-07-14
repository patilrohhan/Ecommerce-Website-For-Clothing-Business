<?php
session_start();
include 'db_connect.php';
$userLoggedIn = isset($_SESSION['username']);
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$username = $userLoggedIn ? $_SESSION['username'] : '';

// If not an AJAX request, proceed to render the cart page HTML.

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if this is an AJAX request for removal or update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    header("Content-Type: application/json");
    $username = $_SESSION['username']; // Assumes the user is logged in
    $product_id = intval($_POST['product_id']);

    if ($_POST['ajax_action'] === 'remove') {
        $product_id = intval($_POST['product_id']);
        // Remove the item from the cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $success = $stmt->execute();
        $stmt->close();

        // Recalculate the cart total
        $stmt = $conn->prepare("SELECT SUM(products.price * cart.quantity) AS total 
                                FROM cart 
                                JOIN products ON cart.product_id = products.id 
                                WHERE cart.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $new_total = $row['total'] ? $row['total'] : 0;
        $stmt->close();

        echo json_encode(['success' => $success, 'new_total' => $new_total]);
        exit;
    } elseif ($_POST['ajax_action'] === 'update') {
        $product_id = intval($_POST['product_id']);
        $quantity   = intval($_POST['quantity']);

        // Update the quantity in the cart
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $success = $stmt->execute();
        $stmt->close();

        // Get the new subtotal for this product
        $stmt = $conn->prepare("SELECT products.price, cart.quantity 
                                FROM cart 
                                JOIN products ON cart.product_id = products.id 
                                WHERE cart.user_id = ? AND cart.product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $new_subtotal = $row ? $row['price'] * $row['quantity'] : 0;
        $stmt->close();

        // Recalculate overall total
        $stmt = $conn->prepare("SELECT SUM(products.price * cart.quantity) AS total 
                                FROM cart 
                                JOIN products ON cart.product_id = products.id 
                                WHERE cart.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $new_total = $row['total'] ? $row['total'] : 0;
        $stmt->close();

        echo json_encode([
          'success'      => $success,
          'new_subtotal' => $new_subtotal,
          'new_total'    => $new_total
        ]);
        exit;
    }
}



// Prepare a query joining the cart with the products table.
$stmt = $conn->prepare("SELECT cart.product_id, cart.quantity, products.title, products.price, products.image 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/00ea09fcaa.js"></script>
  <title>SkyShop - Cart</title>
  <link rel="stylesheet" href="style.css">
  <!-- Additional CSS for Fade-In Animation and Cart Styling -->
  <style>
    /* Fade-In Animation */
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .fade-in.appear {
      opacity: 1;
      transform: translateY(0);
    }
    /* Cart Table Styling */
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background-color: #f5f5f5;
    }
    th, td {
      padding: 10px;
      text-align: center;
      vertical-align: middle;
      border-bottom: 1px solid #ddd;
    }
    td img {
      max-width: 60px;
    }
    input[type=number] {
      width: 60px;
      padding: 5px;
      text-align: center;
    }
    .remove-item, .update-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #e74c3c;
    }
    .remove-item:hover {
      color: #c0392b;
    }
    .normal {
      background-color: #088178;
      color: #fff;
      padding: 8px 12px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <section id="header" class="fade-in">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <div>
      <ul id="navbar">
        <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li id="lg-bag"><a class="active" href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
        <?php if ($userLoggedIn): ?>
          <li id="user-login">
            <a href="profile.php"><i class="fa-solid fa-user"></i></a>
          </li>
          <?php endif; ?>
      </ul>
    </div>
    <div id="mobile">
      <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
      <i id="bar" class="fa-solid fa-bars"></i>
    </div>
  </section>

  <!-- Page Header Section -->
  <section id="page-header" class="about-header fade-in">
    <h2>#Cart</h2>
    <p>Review your items and update your cart</p>
  </section>

  <!-- Cart Table Section -->
  <section id="cart" class="section-p1 fade-in">
    <table id="cart-table">
      <thead>
        <tr>
          <th>Remove</th>
          <th>Image</th>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): 
                  $price    = $row['price'];
                  $quantity = $row['quantity'];
                  $subtotal = $price * $quantity;
                  $total   += $subtotal;
          ?>
            <tr data-product-id="<?php echo $row['product_id']; ?>">
              <td>
                <button class="remove-item" data-product-id="<?php echo $row['product_id']; ?>">
                  <i class="far fa-times-circle"></i>
                </button>
              </td>
              <td>
                <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
              </td>
              <td><?php echo htmlspecialchars($row['title']); ?></td>
              <td>$<?php echo number_format($price, 2); ?></td>
              <td>
                <form class="update-quantity-form">
                  <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                  <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1">
                  
                </form>
              </td>
              <td class="subtotal">$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">Your cart is empty.</td>
          </tr>
        <?php endif; ?>
        <?php $stmt->close(); ?>
      </tbody>
    </table>
  </section>

  <!-- Coupon and Subtotal Section -->
  <section id="cart-add" class="section-p1 fade-in">
    <div id="coupon">
      <h3>Apply Coupon</h3>
      <div>
        <input type="text" placeholder="Enter the Coupon" name="coupon">
        <button class="normal">Apply</button>
      </div>
    </div>

    <div id="subtotal">
      <h3>Cart Total</h3>
      <table>
        <tr>
          <td>Cart Total</td>
          <td id="cart-total">$<?php echo number_format($total, 2); ?></td>
        </tr>
        <tr>
          <td>Shipping</td>
          <td>Free</td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td id="grand-total"><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
      </table>
      <button id="proceed-checkout" class="normal">Proceed to Checkout</button>
    </div>
  </section>

  <script>
  document.getElementById('proceed-checkout').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'checkout.php';
  });
</script>

  <!-- Newsletter Section -->
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

  <!-- Intersection Observer for Fade-In Animation -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const faders = document.querySelectorAll('.fade-in');
      const appearOptions = {
        threshold: 0.2,
        rootMargin: "0px 0px -50px 0px"
      };
      const appearOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('appear');
          observer.unobserve(entry.target);
        });
      }, appearOptions);
      faders.forEach(fader => {
        appearOnScroll.observe(fader);
      });
    });
  </script>

  <!-- AJAX Functionality for Removing and Updating Cart Items -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      // Function to update the cart totals on the page
      function updateCartTotals(newTotal) {
        document.getElementById("cart-total").textContent = "$" + parseFloat(newTotal).toFixed(2);
        document.getElementById("grand-total").innerHTML = "<strong>$" + parseFloat(newTotal).toFixed(2) + "</strong>";
      }

      // Remove item via AJAX
      document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", (e) => {
          e.preventDefault();
          const productId = button.getAttribute("data-product-id");
          
          fetch("cart.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "ajax_action=remove&product_id=" + encodeURIComponent(productId)
          })
          .then(response => response.json())
          .then(data => {
            if(data.success) {
              // Remove the row from the table
              const row = button.closest("tr");
              row.parentNode.removeChild(row);
              // Update total on page
              updateCartTotals(data.new_total);
            } else {
              alert("Failed to remove product.");
            }
          })
          .catch(error => {
            console.error("Error:", error);
          });
        });
      });

      // Update quantity via AJAX
      document.querySelectorAll(".update-quantity-form").forEach(form => {
        form.addEventListener("submit", (e) => {
          e.preventDefault();
          const formData = new FormData(form);
          // Append the ajax action to the form data
          formData.append("ajax_action", "update");
          
          fetch("cart.php", {
            method: "POST",
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.success) {
              // Update subtotal for this row
              const row = form.closest("tr");
              row.querySelector(".subtotal").textContent = "$" + parseFloat(data.new_subtotal).toFixed(2);
              // Update overall total
              updateCartTotals(data.new_total);
            } else {
              alert("Failed to update quantity.");
            }
          })
          .catch(error => {
            console.error("Error:", error);
          });
        });
      });
    });
  </script>
</body>
</html>
