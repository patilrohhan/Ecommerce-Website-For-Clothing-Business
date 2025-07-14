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
  <title>SkyShop - Contact</title>
  <link rel="stylesheet" href="style.css">
  <!-- Additional CSS for Fade-In Animation and Search Form Styling -->
  <style>
    /* Fade-In Animation Styles */
    .fade-in {
      opacity: 1;
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
        <li><a href="about.php">About</a></li>
        <li><a class="active" href="contact.php">Contact</a></li>
        <!-- Note: No static cart icon on Contact page -->
        
      </ul>
    </div>
    <div id="mobile">
      <!-- Mobile version: cart icon will be added dynamically if logged in -->
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
      // For a consistent login state, you can rely on the PHP variable via the injected loggedIn,
      // or use localStorage if your login process also sets it.
      // Here we check the PHP-provided login state (loggedIn variable).
      // Append search icon to the navbar
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
        // Insert a cart icon if user is logged in
        let cartIconLi = document.createElement("li");
        cartIconLi.id = "lg-bag";
        cartIconLi.innerHTML = '<a href="cart.php"><i class="fa-solid fa-bag-shopping"></i><span id="cart-count"></span></a>';
        navbar.appendChild(cartIconLi);
        // Insert the profile icon
        let userLinkLi = document.createElement("li");
        userLinkLi.id = "user-login";
        userLinkLi.innerHTML = '<a href="profile.php"><i class="fa-solid fa-user"></i></a>';
        navbar.appendChild(userLinkLi);
      } else {
        // If not logged in, insert only the sign-in icon
        let userLinkLi = document.createElement("li");
        userLinkLi.id = "user-login";
        userLinkLi.innerHTML = '<a href="signin.php"><i class="fa-solid fa-user-plus"></i></a>';
        navbar.appendChild(userLinkLi);
      }
      
    });
  </script>

  <!-- Page Header Section with Fade-In -->
  <section id="page-header" class="about-header fade-in">
    <h2>#Lets_Talk</h2>
    <p>LEAVE A MESSAGE, We love to hear from you!</p>
  </section>

  <!-- Contact Details Section with Fade-In -->
  <section id="contact-details" class="section-p1 fade-in">
    <div class="details">
      <span>GET IN TOUCH</span>
      <h2>Visit one of our agency locations or contact us today</h2>
      <h3>Head Office</h3>
      <div>
        <li>
          <i class="far fa-map"></i>
          <p>Vikhroli Link Rd, Vidya Milind Nagar, Marol, Andheri East, India</p>
        </li>
        <li>
          <i class="far fa-envelope"></i>
          <p>contact@example.com</p>
        </li>
        <li>
          <i class="fas fa-phone-alt"></i>
          <p>08044310000</p>
        </li>
        <li>
          <i class="far fa-clock"></i>
          <p>Monday to Saturday, 10:00 AM to 05:00 PM</p>
        </li>
      </div>
    </div>
    <div class="map fade-in">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3767.3979586466157!2d72.85947467503125!3d19.221480482013266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b7003f1fd211%3A0xfd51eec2537262b4!2sSky%20City%20-%20Main%20Entry%20Gate!5e0!3m2!1sen!2sin!4v1738682380902!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>

  <!-- Form Details Section with Fade-In -->
  <section id="form-details" class="fade-in">
    <form action="">
      <span>LEAVE A MESSAGE!</span>
      <h2>We love to hear from you!</h2>
      <input type="text" placeholder="Your Name">
      <input type="text" placeholder="E-Mail">
      <input type="text" placeholder="Subject">
      <textarea cols="30" rows="10" placeholder="Your Message"></textarea>
      <button class="normal">Submit</button>
    </form>
    <div class="people fade-in">
      <div>
        <img src="img/People/1.png" alt="">
        <p><span>Steve Houghs</span> Senior Marketing Manager <br> Phone: +000 123 000 77 98 <br> Email: contact@example.com</p>
      </div>
      <div>
        <img src="img/People/2.png" alt="">
        <p><span>Justin Emma</span> Senior Marketing Manager <br> Phone: +000 123 000 77 98 <br> Email: contact@example.com</p>
      </div>
      <div>
        <img src="img/People/3.png" alt="">
        <p><span>Sam William</span> Senior Marketing Manager <br> Phone: +000 123 000 77 98 <br> Email: contact@example.com</p>
      </div>
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
  
  <!-- (Optional: Additional JS for search functionality if needed) -->
</body>
</html>
