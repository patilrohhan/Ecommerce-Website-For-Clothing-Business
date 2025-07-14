<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/00ea09fcaa.js"></script>
  <title>Sign Up - SkyShop</title>
  <link rel="stylesheet" href="style.css">
  <!-- Additional CSS for Form Styling, Fade-In Animation & Popup Styling -->
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
    /* Form Container */
    .form-container {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    .form-container button {
      width: 100%;
      padding: 10px;
      background: #088178;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      margin-top: 10px;
    }
    .form-container p {
      text-align: center;
      margin-top: 15px;
    }
    .form-container p a {
      color: #088178;
      text-decoration: none;
    }
    /* Popup (Modal) Styling */
    .popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .popup.active {
      display: flex;
    }
    .popup-content {
      background: #fff;
      padding: 20px 30px;
      border-radius: 5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      max-width: 400px;
      text-align: center;
      position: relative;
    }
    .popup-content .close {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      font-size: 18px;
      color: #aaa;
    }
  </style>
</head>
<body>
  <!-- Header (reuse your existing header markup) -->
  <section id="header">
    <a href="index.php"><img src="img/skyshop.png" class="logo" alt="SkyShop Logo"></a>
    <div>
      <ul id="navbar">
        <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li id="search-btn">
          <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
        </li>
        <!-- New Account Icons -->
        <li id="user-signup"><a href="signup.php" class="active"><i class="fa-solid fa-user-plus"></i></a></li>
      </ul>
    </div>
  </section>

  <!-- Sign Up Form Section with Fade-In -->
  <section id="signup" class="section-p1 fade-in">
    <div class="form-container">
      <h2>Create New Account</h2>
      <form id="signupForm" action="register.php" method="post">
        
        <input type="text" id="fullname" name="fullname" placeholder="Your Name" required>
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" class="normal">Sign Up</button>
      </form>
      <p>Already have an account? <a href="signin.html">Sign In</a></p>
    </div>
  </section>

  <!-- Popup Message for Errors -->
  <div id="errorPopup" class="popup">
    <div class="popup-content">
      <span class="close" id="closePopup">&times;</span>
      <p id="popupMessage">User Credentials Already Exist</p>
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

  <!-- Footer (reuse your existing footer markup) -->
  <footer class="section-p1 fade-in">
    <div class="col">
      <img class="logo" src="img/skyshop.png" alt="SkyShop Logo">
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
      <a href="signin.html">Sign In</a>
      <a href="#">View Cart</a>
      <a href="#">My Wishlist</a>
      <a href="#">Track My Order</a>
      <a href="#">Help</a>
    </div>
    <div class="col install">
      <h4>Install App</h4>
      <p>From App Store or Google Play</p>
      <div class="row">
        <a href="#"><img src="img/Pay/app.jpg" alt="App Store"></a>
        <a href="#"><img src="img/Pay/play.jpg" alt="Google Play"></a>
      </div>
      <p>Secured Payment Gateways</p>
      <img src="img/Pay/pay.png" alt="Payment Methods">
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

  <!-- Client-Side Form Validation for Password Matching -->
  <script>
    document.getElementById("signupForm").addEventListener("submit", function(e) {
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert("Passwords do not match. Please re-enter your password.");
      }
    });
  </script>

  <!-- Popup Display Script -->
  <script>
    // Utility to get query parameter value
    function getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }
  
    document.addEventListener("DOMContentLoaded", () => {
      const errorParam = getQueryParam('error');
      if (errorParam === 'userexists') {
        const popup = document.getElementById('errorPopup');
        popup.classList.add('active');
        // Delay removal of query parameter slightly to ensure popup shows first
        setTimeout(() => {
          history.replaceState(null, '', window.location.pathname);
        }, 1000);
      }
  
      // Close button for the popup
      document.getElementById('closePopup').addEventListener('click', () => {
        document.getElementById('errorPopup').classList.remove('active');
      });
    });
  </script>
  
  

  <script type="module" src="/main.js"></script>
</body>
</html>
