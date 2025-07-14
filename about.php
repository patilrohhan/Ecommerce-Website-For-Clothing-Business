<?php
session_start();
$userLoggedIn = isset($_SESSION['username']);
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$username = $userLoggedIn ? $_SESSION['username'] : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- FontAwesome for icons -->
  <script src="https://kit.fontawesome.com/00ea09fcaa.js" crossorigin="anonymous"></script>
  <title>SkyShop - About</title>
  <link rel="stylesheet" href="style.css">
  <!-- Additional CSS for Fade-In Animation and Search Form Styling -->
  <style>
    /* Fade-In Animation Styles */
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .fade-in.appear {
      opacity: 1;
      transform: translateY(0);
    }
    /* Search Form Styling (same as index page) */
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
    #search-form.active {
      display: block;
    }
    #search-form input {
      border: none;
      outline: none;
      padding: 5px;
      font-size: 16px;
      width: 200px;
    }
    #search-form button {
      background: #088178;
      color: #fff;
      border: none;
      padding: 5px 10px;
      margin-left: 5px;
      cursor: pointer;
      border-radius: 3px;
    }
    /* Suggestions Container */
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
    #suggestions-container div {
      padding: 5px;
      cursor: pointer;
    }
    #suggestions-container div:hover {
      background: #f0f0f0;
    }
    /* Cart Count Styling */
    #lg-bag a {
      position: relative;
    }
    #lg-bag a #cart-count {
      position: absolute;
      top: -5px;
      right: -10px;
      background: red;
      color: #fff;
      font-size: 12px;
      padding: 2px 5px;
      border-radius: 50%;
      display: none; /* Hidden when count is 0 */
    }
  </style>
  <!-- Inject PHP login state into a JS variable -->
  <script>
    var loggedIn = <?php echo $userLoggedIn ? 'true' : 'false'; ?>;
  </script>
</head>
<body>
  <!-- Header Section -->
  <section id="header">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <div>
      <ul id="navbar">
        <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a class="active" href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <!-- Note: The static markup does not include a cart icon -->
      </ul>
    </div>
    <div id="mobile">
      <!-- Mobile version: dynamic icons will be inserted -->
      <i id="bar" class="fa-solid fa-bars"></i>
    </div>
    <!-- Hidden Search Form -->
    <div id="search-form">
      <input type="text" placeholder="Search...">
      <button class="normal">Search</button>
      <div id="suggestions-container"></div>
    </div>
  </section>

  <!-- Dynamic Navbar Insertion -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const navbar = document.getElementById("navbar");
      // Use the PHP session login state via the injected 'loggedIn' variable

      // Append the search icon to the navbar
      let searchIconLi = document.createElement("li");
      searchIconLi.id = "search-btn";
      searchIconLi.innerHTML = '<a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>';
      navbar.appendChild(searchIconLi);
      // Toggle search form when the search icon is clicked
      document.getElementById("search-btn").addEventListener("click", (e) => {
         e.preventDefault();
         document.getElementById("search-form").classList.toggle("active");
      });
      if (loggedIn === true) {
        // Insert a cart icon with a hidden cart count
        let cartIconLi = document.createElement("li");
        cartIconLi.id = "lg-bag";
        cartIconLi.innerHTML = '<a href="cart.php"><i class="fa-solid fa-bag-shopping"></i><span id="cart-count">0</span></a>';
        navbar.appendChild(cartIconLi);
        // Insert the profile icon linking to profile.php
        let userLinkLi = document.createElement("li");
        userLinkLi.id = "user-login";
        userLinkLi.innerHTML = '<a href="profile.php"><i class="fa-solid fa-user"></i></a>';
        navbar.appendChild(userLinkLi);
      } else {
        // If not logged in, insert only a sign-in icon linking to signin.php
        let userLinkLi = document.createElement("li");
        userLinkLi.id = "user-login";
        userLinkLi.innerHTML = '<a href="signin.php"><i class="fa-solid fa-user-plus"></i></a>';
        navbar.appendChild(userLinkLi);
      }
      
    });
  </script>

  <!-- Page Header Section with Fade-In -->
  <section id="page-header" class="about-header fade-in">
    <h2>#KnowUs</h2>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing.</p>
  </section>

  <!-- About Head Section with Fade-In -->
  <section id="about-head" class="section-p1 fade-in">
    <img src="img/About/a6.jpg" alt="">
    <div>
      <h2>Who We Are?</h2>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore impedit, non corporis vero molestiae quaerat a accusamus reprehenderit? Voluptate eligendi voluptatum ullam nostrum temporibus? Deserunt ex fugiat sequi. Nulla in quas omnis laboriosam omnis est atque esse voluptatem tempora error recusandae. Aliquid ab, et excepturi hic distinctio beatae iusto maiores molestiae quos, asperiores quas animi similique, alias tempore.
      </p>
      <br>
      <marquee bgcolor="#ccc" loop="-1" scrollamount="5" width="100%">
        Create stunning images with as much or as little control as you like thanks to a choice of basics and creative models.
      </marquee>
    </div>
  </section>

  <!-- About App Section with Fade-In -->
  <section id="about-app" class="section-p1 fade-in">
    <h1>Download Our <a href="#">App</a></h1>
    <div class="video">
      <video autoplay muted loop src="img/About/1.mp4"></video>
    </div>
  </section>

  <!-- Features Section with Fade-In -->
  <section id="feature" class="section-p1 fade-in">
    <div class="fe-box">
      <img src="img/Features/f2.png" alt="">
      <h6>Free Shipping</h6>
    </div>
    <div class="fe-box">
      <img src="img/Features/f1.png" alt="">
      <h6>Online Order</h6>
    </div>
    <div class="fe-box">
      <img src="img/Features/f3.png" alt="">
      <h6>Save Money</h6>
    </div>
    <div class="fe-box">
      <img src="img/Features/f4.png" alt="">
      <h6>Promotions</h6>
    </div>
    <div class="fe-box">
      <img src="img/Features/f5.png" alt="">
      <h6>Happy Sell</h6>
    </div>
    <div class="fe-box">
      <img src="img/Features/f6.png" alt="">
      <h6>24/7 Support</h6>
    </div>
  </section>

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

  <!-- Footer Section (static) -->
  <footer class="section-p1">
    <div class="col">
      <img class="logo" src="img/skyshop.png" alt="">
      <h4>Contact</h4>
      <p><strong>Address:</strong> 191 Ring Road, Street 18, Jalgaon, Maharashtra</p>
      <p><strong>Phone:</strong> +91 2224 1546 / +91 01 2546 5544</p>
      <p><strong>Hours:</strong> 10:00 - 18:00, Mon - Sat</p>
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
</body>
</html>
