<?php
session_start();
// $userLoggedIn is true if the session variable is set.
$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? $_SESSION['username'] : '';
// Retrieve and decode the product name from the URL query string
$productParam = isset($_GET['product']) ? urldecode(trim($_GET['product'])) : '';

if (!$productParam) {
    echo "No product specified.";
    exit;
}

// Define an associative array mapping product names to their details
$products = [
    "Cartoon Astronaut Shirts" => [
        "id"          => 1,
        "title"       => "Cartoon Astronaut Shirts",
        "brand"       => "Zara",
        "price"       => "$79",
        "image"       => "img/Products/f1.jpg",
        "description" => "A trendy cartoon astronaut shirt with a fun design."
    ],
    "Floral Print Shirts" => [
        "id"          => 2,
        "title"       => "Floral Print Shirts",
        "brand"       => "Zara",
        "price"       => "$75",
        "image"       => "img/Products/f2.jpg",
        "description" => "A stylish floral print shirt perfect for summer."
    ],
    "Ornament Print Shirts" => [
        "id"          => 3,
        "title"       => "Ornament Print Shirts",
        "brand"       => "Zara",
        "price"       => "$89",
        "image"       => "img/Products/f3.jpg",
        "description" => "An elegant ornament print shirt for a formal look."
    ],
    "Paisley Print Shirts" => [
        "id"          => 4,
        "title"       => "Paisley Print Shirts",
        "brand"       => "Raymond",
        "price"       => "$68",
        "image"       => "img/Products/f4.jpg",
        "description" => "A classic paisley print shirt with rich patterns."
    ],
    "Abstract Print Shirts" => [
        "id"          => 5,
        "title"       => "Abstract Print Shirts",
        "brand"       => "Zara",
        "price"       => "$49",
        "image"       => "img/Products/f5.jpg",
        "description" => "A modern abstract print shirt with vibrant colors."
    ],
    "Boxy Fit Shirts" => [
        "id"          => 6,
        "title"       => "Boxy Fit Shirts",
        "brand"       => "Zara",
        "price"       => "$109",
        "image"       => "img/Products/f6.jpg",
        "description" => "A trendy boxy fit shirt that offers a relaxed look."
    ],
    "Classy Curve Pants" => [
        "id"          => 7,
        "title"       => "Classy Curve Pants",
        "brand"       => "Zara",
        "price"       => "$85",
        "image"       => "img/Products/f7.jpg",
        "description" => "Elegant and well-fitting pants for a classy look."
    ],
    "Ciba Dresses" => [
        "id"          => 8,
        "title"       => "Ciba Dresses",
        "brand"       => "Zara",
        "price"       => "$58",
        "image"       => "img/Products/f8.jpg",
        "description" => "A chic ciba dress for casual outings."
    ],
    "Formal Skyblue Shirts" => [
    "id"          => 9,
    "title"       => "Formal Skyblue Shirts",
    "brand"       => "Raymond",
    "price"       => "$79",
    "image"       => "img/Products/n1.jpg",
    "description" => "A formal skyblue shirt perfect for business occasions."
  ],
  "Floral Shirts" => [
    "id"          => 10,
    "title"       => "Floral Shirts",
    "brand"       => "H&M",
    "price"       => "$75",
    "image"       => "img/Products/n2.jpg",
    "description" => "A vibrant floral shirt that adds a touch of freshness."
  ],
  "Ornament White Shirts" => [
    "id"          => 11,
    "title"       => "Ornament White Shirts",
    "brand"       => "Ajio",
    "price"       => "$89",
    "image"       => "img/Products/n3.jpg",
    "description" => "A stylish white shirt with intricate ornament details."
  ],
  "Paisley Print Shirts" => [
    "id"          => 12,
    "title"       => "Paisley Print Shirts",
    "brand"       => "Zara",
    "price"       => "$68",
    "image"       => "img/Products/n4.jpg",
    "description" => "A classic paisley print shirt that suits any occasion."
  ],
  "Denim Shirts" => [
    "id"          => 13,
    "title"       => "Denim Shirts",
    "brand"       => "Denim",
    "price"       => "$49",
    "image"       => "img/Products/n5.jpg",
    "description" => "A cool denim shirt for a casual, laid-back look."
  ],
  "Relaxed Fit Trauser" => [
    "id"          => 14,
    "title"       => "Relaxed Fit Trauser",
    "brand"       => "H&M",
    "price"       => "$109",
    "image"       => "img/Products/n6.jpg",
    "description" => "Comfortable and stylish trousers with a relaxed fit."
  ],
  "Classy Duble Pocket" => [
    "id"          => 15,
    "title"       => "Classy Duble Pocket",
    "brand"       => "Dymond",
    "price"       => "$85",
    "image"       => "img/Products/n7.jpg",
    "description" => "A trendy double pocket design that enhances style."
  ],
  "Ciba Dresses" => [
    "id"          => 16,
    "title"       => "Ciba Dresses",
    "brand"       => "Curve",
    "price"       => "$58",
    "image"       => "img/Products/n8.jpg",
    "description" => "A chic and casual ciba dress perfect for outings."
  ]
];

if (!array_key_exists($productParam, $products)) {
    echo "Product not found.";
    exit;
}

$product = $products[$productParam];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/00ea09fcaa.js"></script>
  <title><?php echo htmlspecialchars($product['title']); ?> - Product Details</title>
  <link rel="stylesheet" href="style.css">
  <script>
    var loggedIn = <?php echo json_encode($userLoggedIn); ?>;
    console.log("loggedIn (from PHP):", loggedIn);
  </script>

  <!-- Additional CSS for new functionality -->
  <style>
    /* --- Popup Message Styling --- */
    .popup-message {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #4caf50;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      opacity: 0;
      transition: opacity 0.5s;
      z-index: 9999;
    }
    .popup-message.active { opacity: 1; }
 /* --- Search Form Styling --- */
 #search-form {
      display: none;
      position: absolute;
      top: 60px;
      right: 20px;
      background: #fff;
      border: 1px solid #ccc;
      padding: 10px;
      z-index: 1000;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      border-radius: 5px;
    }
    #search-form.active { display: block; }
    #search-form input { border: none; outline: none; padding: 5px; font-size: 16px; width: 200px; }
    #search-form button {
      background: #088178;
      color: #fff;
      border: none;
      padding: 5px 10px;
      margin-left: 5px;
      cursor: pointer;
      border-radius: 3px;
    }
    /* --- Suggestions Container --- */
    #suggestions-container {
      position: absolute;
      top: 40px;
      left: 0;
      right: 0;
      background: #fff;
      border: 1px solid #ccc;
      z-index: 1001;
      display: none;
      max-height: 200px;
      overflow-y: auto;
    }
    #suggestions-container div { padding: 5px; cursor: pointer; }
    #suggestions-container div:hover { background: #f0f0f0; }
    /* --- Animated Popup Message --- */
    .popup-message {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #4caf50;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      opacity: 0;
      transition: opacity 0.5s;
      z-index: 9999;
    }
    .popup-message.active { opacity: 1; }
    /* --- Fade-In Animation Styling --- */
    .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 1s ease-out, transform 1s ease-out; }
    .fade-in.appear { opacity: 1; transform: translateY(0); }

    /* --- Zoom-In Effect for Product Card Images --- */
    /* Ensure the card hides any overflow so the image zoom remains inside */
    .pro {
      overflow: hidden;
    }
    .pro img {
      transition: transform 0.4s ease-in-out;
    }
    .pro:hover img {
      transform: scale(1.08);
    }

    /* --- Basic Styling for Product Details --- */
    .product-details {
      max-width: 800px;
      margin: 30px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background: #fff;
    }
    .product-details img { max-width: 100%; margin-bottom: 20px; }
    .product-details h1 { margin-bottom: 10px; }
    .product-details .brand, .product-details .price { font-size: 18px; margin-bottom: 10px; }
    .product-details .description { font-size: 16px; }
    .product-details button {
      padding: 10px 20px;
      background: #088178;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <!-- Add the Popup Message element -->
  <div id="popup-message" class="popup-message"></div>

  <!-- Full Header Section with Navbar -->
  <section id="header" class="fade-in">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <div>
      <ul id="navbar">
        <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <!-- Cart Icon -->
        <li id="lg-bag">
          <a href="cart.php">
            <i class="fa-solid fa-bag-shopping"></i>
            <span id="cart-count"></span>
          </a>
        </li>
        <!-- Search Icon -->
        <li id="search-btn">
          <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
        </li>
      </ul>
    </div>
    <div id="mobile">
      <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
      <i id="bar" class="fa-solid fa-bars"></i>
    </div>
    <!-- Hidden Search Form -->
    <div id="search-form">
      <input type="text" placeholder="Search...">
      <button class="normal">Search</button>
      <div id="suggestions-container"></div>
    </div>
  </section>

  <!-- Dynamically insert user login icon into navbar -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      console.log("Document loaded. LoggedIn value is:", loggedIn);
      const navbar = document.getElementById("navbar");
      let userLinkLi = document.createElement("li");
      userLinkLi.id = "user-login";
      if (loggedIn) {
        userLinkLi.innerHTML = `<a href="profile.php"><i class="fa-solid fa-user"></i></a>`;
        document.getElementById("lg-bag").style.display = "inline-block";
      } else {
        userLinkLi.innerHTML = `<a href="signin.php"><i class="fa-solid fa-user-plus"></i></a>`;
        document.getElementById("lg-bag").style.display = "none";
      }
      const searchBtnLi = document.getElementById("search-btn");
      navbar.insertBefore(userLinkLi, searchBtnLi);
    });
  </script>

  <!-- Product Details Section -->
  <section id="prodetails" class="section-p1 fade-in">
      <img class="single-pro-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
    <div class="single-pro-details">
      <h6><div class="brand"><?php echo htmlspecialchars($product['brand']); ?></div></h6>
      <h4><?php echo htmlspecialchars($product['title']); ?></h4>
      <h2><div class="price"><?php echo htmlspecialchars($product['price']); ?></div></h2>
      <select>
        <option>Select Size</option>
        <option>S</option>
        <option>M</option>
        <option>L</option>
        <option>XL</option>
        <option>XXL</option>
      </select>
      <input type="number" value="1">
      <button class="normal add-to-cart" id="detail-add-to-cart">Add to Cart</button>
      <h4>Product Details</h4>
      <span class="description"><?php echo htmlspecialchars($product['description']); ?> Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas suscipit, animi unde sapiente architecto omnis soluta reiciendis nobis, praesentium error possimus debitis voluptates porro fuga incidunt...</span>
    </div>
  </section>

  <section id="product1" class="section-p1 fade-in">
    <h2>Featured Products</h2>
    <p>Styles That Go The Distance</p>
    <div class="pro-container">
      <!-- Product Card 1 -->
      <div class="pro" onclick="window.location.href='sproduct.php?product=Cartoon%20Astronaut%20Shirts';">
        <img src="img/Products/f1.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Cartoon Astronaut Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$79</h4>
        </div>
        <a href="#" class="add-to-cart" ><i class="fa-solid fa-cart-shopping"></i></a>
      </div>
      <!-- Product Card 2 -->
      <div class="pro" onclick="window.location.href='sproduct.php?product=Floral%20Print%20Shirts';">
        <img src="img/Products/f2.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Floral Print Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$75</h4>
        </div>
        <a href="#" class="add-to-cart"><i class="fa-solid fa-cart-shopping"></i></a>
      </div>
      <!-- Product Card 3 -->
      <div class="pro" onclick="window.location.href='sproduct.php?product=Ornament%20Print%20Shirts';">
        <img src="img/Products/f3.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Ornament Print Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$89</h4>
        </div>
        <a href="#" class="add-to-cart"><i class="fa-solid fa-cart-shopping"></i></a>
      </div>
      <!-- Product Card 4 -->
      <div class="pro" onclick="window.location.href='sproduct.php?product=Paisley%20Print%20Shirts';">
        <img src="img/Products/f4.jpg" alt="">
        <div class="des">
          <span>Raymond</span>
          <h5>Paisley Print Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$68</h4>
        </div>
        <a href="#" class="add-to-cart"><i class="fa-solid fa-cart-shopping"></i></a>
      </div>
    </div>
  </section>

  <!-- Newsletter Section (consistent with other pages) -->
  <section id="newsletter" class="section-p1 section-m1">
    <div class="newstext">
      <h4>Sign Up For Newsletters</h4>
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

  <!-- Existing JS for functionality -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      /* --- Standard Popup Message Function --- */
      function showPopup(message) {
        const popup = document.getElementById("popup-message");
        if (!popup) return;
        popup.textContent = message;
        popup.classList.add("active");
        setTimeout(() => {
          popup.classList.remove("active");
        }, 2000);
      }

      // Dummy function for login popup if not already defined
      function showLoginPopup(message) {
        // Replace this with your custom login popup logic
        alert(message);
      }

      // Dummy function for updating cart count if not defined
      function updateCartCount() {
        // Your logic to update cart count goes here
        console.log("Cart count updated.");
      }

      /* --- Add-to-Cart Functionality for Product Details --- */
      const detailAddToCartButton = document.getElementById("detail-add-to-cart");
      if (detailAddToCartButton) {
        detailAddToCartButton.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();

          // Check if the user is logged in using the PHP-injected variable
          if (!loggedIn) {
            showLoginPopup("Please log in first to add items to your cart!");
            window.location.href = "signin.php";
            return;
          }

          // Retrieve product data from the product details page elements
          const productId = "<?php echo $product['id']; ?>"; // Adjust if you have a numeric id
          const productTitle = document.querySelector(".single-pro-details h4").textContent.trim();
          const productImage = document.querySelector(".single-pro-image").src;
          const productPrice = document.querySelector(".single-pro-details .price").textContent.trim();
          const quantity = 1; // Default quantity

          // Build the form data to send via AJAX (same as shop page)
          const formData = new FormData();
          formData.append("product_id", productId);
          formData.append("product_title", productTitle);
          formData.append("product_image", productImage);
          formData.append("product_price", productPrice);
          formData.append("quantity", quantity);

          // Send AJAX POST request to add_to_cart.php
          fetch("add_to_cart.php", {
            method: "POST",
            body: formData
          })
          .then(response => response.text())
          .then(data => {
            showPopup("Item added to cart!");
            updateCartCount();
          })
          .catch(error => {
            console.error("Error:", error);
            showPopup("Failed to add item to cart!");
          });
        });
      }
    });
  </script>

  <script type="module" src="/main.js"></script>
</body>
</html>
