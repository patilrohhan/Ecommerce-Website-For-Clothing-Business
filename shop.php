<?php
session_start();
// Check if user is logged in (assuming you set $_SESSION['username'] on login)
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
  <title>SkyShop</title>
  <link rel="stylesheet" href="style.css">
  <!-- Additional CSS for new functionality -->
  <style>
    
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
    #suggestions-container div {
      padding: 5px;
      cursor: pointer;
    }
    #suggestions-container div:hover {
      background: #f0f0f0;
    }
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
    .popup-message.active {
      opacity: 1;
    }
    /* --- Fade-In Animation Styling --- */
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .fade-in.appear {
      opacity: 1;
      transform: translateY(0);
    }
    /* --- Pagination Styling --- */
    #pagination {
      text-align: center;
      margin: 20px 0;
    }
    @keyframes pop {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); }
      100% { transform: scale(1); }
    }
    /* --- Slider Container for Products --- */
    #slider {
      display: flex;
      width: 200%;
      transition: transform 0.8s ease-in-out;
    }
    .slide {
      width: 50%;
      padding: 25px;
      padding-bottom: 0;
      flex-shrink: 0;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    /* --- Zoom-In Effect for Product Card Images --- */
    .pro {
      overflow: hidden;
    }
    .pro img {
      transition: transform 0.4s ease-in-out;
    }
    .pro:hover img {
      transform: scale(1.08);
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
        <li><a class="active" href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if ($userLoggedIn): ?>
          <li id="lg-bag">
            <a href="cart.php">
              <i class="fa-solid fa-bag-shopping"></i>
              <span id="cart-count"></span>
            </a>
          </li>
        <?php else: ?>
          <li id="lg-bag" style="display: none;"></li>
        <?php endif; ?>
        <li id="search-btn">
          <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
        </li>
        <?php if ($userLoggedIn): ?>
          <li id="user-login">
            <a href="profile.php"><i class="fa-solid fa-user"></i></a>
          </li>
        <?php else: ?>
          <li id="user-login">
            <a href="signin.php"><i class="fa-solid fa-user-plus"></i></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
    <div id="mobile">
      <?php if ($userLoggedIn): ?>
        <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
      <?php endif; ?>
      <i id="bar" class="fa-solid fa-bars"></i>
    </div>
    <!-- Hidden Search Form -->
    <div id="search-form">
      <input type="text" placeholder="Search...">
      <button class="normal">Search</button>
      <div id="suggestions-container"></div>
    </div>
  </section>

  <!-- Set JavaScript variable using PHP session -->
  <script>
    var loggedIn = <?php echo $userLoggedIn ? 'true' : 'false'; ?>;
  </script>

  <!-- Page Header Section with Fade-In -->
  <section id="page-header" class="fade-in">
    <div class="shoptext">
      <h2>#stayhome</h2>
      <p>Save more with coupons & upto 50% off!</p>
    </div>
  </section>

  <!-- Products Section with Fade-In and Slider -->
  <section id="product1" class="section-p1 fade-in">
    <div id="slider">
      <!-- Slide 1: Featured Products -->
      <div class="pro-container slide" id="page1">
        <!-- Product Card 1 -->
      <div class="pro" 
      data-product-id="1" 
      data-product-title="Cartoon Astronaut Shirts" 
      data-product-image="img/Products/f1.jpg" 
      data-product-price="79"
     onclick="window.location.href='sproduct.php?product=Cartoon%20Astronaut%20Shirts';">
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
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 2 -->
      <div class="pro" 
      data-product-id="2" 
      data-product-title="Floral Print Shirts" 
      data-product-image="img/Products/f2.jpg" 
      data-product-price="75"
      onclick="window.location.href='sproduct.php?product=Floral%20Print%20Shirts';">
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
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 3 -->
      <div class="pro" 
      data-product-id="3" 
      data-product-title="Ornament Print Shirts" 
      data-product-image="img/Products/f3.jpg" 
      data-product-price="89"
      onclick="window.location.href='sproduct.php?product=Ornament%20Print%20Shirts';">
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
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 4 -->
      <div class="pro" 
      data-product-id="4" 
      data-product-title="Paisley Print Shirts" 
      data-product-image="img/Products/f4.jpg" 
      data-product-price="68"
      onclick="window.location.href='sproduct.php?product=Paisley%20Print%20Shirts';">
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
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 5 -->
      <div class="pro" 
      data-product-id="5" 
      data-product-title="Abstract Print Shirts" 
      data-product-image="img/Products/f5.jpg" 
      data-product-price="49"
      onclick="window.location.href='sproduct.php?product=Abstract%20Print%20Shirts';">
        <img src="img/Products/f5.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Abstract Print Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$49</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 6 -->
      <div class="pro" 
      data-product-id="6" 
      data-product-title="Boxy Fit Shirts" 
      data-product-image="img/Products/f6.jpg" 
      data-product-price="109"
      onclick="window.location.href='sproduct.php?product=Boxy%20Fit%20Shirts';">
        <img src="img/Products/f6.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Boxy Fit Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$109</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 7 -->
      <div class="pro" 
      data-product-id="7" 
      data-product-title="Classy Curve Pants" 
      data-product-image="img/Products/f7.jpg" 
      data-product-price="85"
      onclick="window.location.href='sproduct.php?product=Classy%20Curve%20Pants';">

        <img src="img/Products/f7.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Classy Curve Pants</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$85</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- Product Card 8 -->
      <div class="pro" 
      data-product-id="8" 
      data-product-title="Ciba Dresses" 
      data-product-image="img/Products/f8.jpg" 
      data-product-price="58"
      onclick="window.location.href='sproduct.php?product=Ciba%20Dresses';">
        <img src="img/Products/f8.jpg" alt="">
        <div class="des">
          <span>Zara</span>
          <h5>Ciba Dresses</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$58</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      </div>
      <!-- Slide 2: New Arrivals -->
      <div class="pro-container slide" id="page2">
        <!-- (Repeat similar structure for New Arrivals product cards, updating onclick URLs to sproduct.php if needed) -->
        <!-- Example New Arrival Card -->
        <div class="pro" 
      data-product-id="9" 
      data-product-title="Formal Skyblue Shirts" 
      data-product-image="img/Products/n1.jpg" 
      data-product-price="79"
      onclick="window.location.href='sproduct.php?product=Formal%20Skyblue%20Shirts';">
        <img src="img/Products/n1.jpg" alt="">
        <div class="des">
          <span>Raymond</span>
          <h5>Formal Skyblue Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$79</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 2 -->
      <div class="pro" 
      data-product-id="10" 
      data-product-title="Floral Shirts" 
      data-product-image="img/Products/n2.jpg" 
      data-product-price="75"
      onclick="window.location.href='sproduct.php?product=Floral%20Shirts';">
        <img src="img/Products/n2.jpg" alt="">
        <div class="des">
          <span>H&M</span>
          <h5>Floral Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$75</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 3 -->
      <div class="pro" 
      data-product-id="11" 
      data-product-title="Ornament White Shirts" 
      data-product-image="img/Products/n3.jpg" 
      data-product-price="89"
      onclick="window.location.href='sproduct.php?product=Ornament%20White%20Shirts';">
        <img src="img/Products/n3.jpg" alt="">
        <div class="des">
          <span>Ajio</span>
          <h5>Ornament White Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$89</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 4 -->
      <div class="pro" 
      data-product-id="12" 
      data-product-title="Paisley Print Shirts" 
      data-product-image="img/Products/n4.jpg" 
      data-product-price="68"
      onclick="window.location.href='sproduct.php?product=Paisley%20Print%20Shirts';">
        <img src="img/Products/n4.jpg" alt="">
        <div class="des">
          <span>Zara</span>
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
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 5 -->
      <div class="pro" 
      data-product-id="13" 
      data-product-title="Denim Shirts" 
      data-product-image="img/Products/n5.jpg" 
      data-product-price="49"
      onclick="window.location.href='sproduct.php?product=Denim%20Shirts';">
        <img src="img/Products/n5.jpg" alt="">
        <div class="des">
          <span>Denim</span>
          <h5>Denim Shirts</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$49</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 6 -->
      <div class="pro" 
      data-product-id="14" 
      data-product-title="Relaxed Fit Trauser" 
      data-product-image="img/Products/n6.jpg" 
      data-product-price="109"
      onclick="window.location.href='sproduct.php?product=Relaxed%20Fit%20Trauser';">
        <img src="img/Products/n6.jpg" alt="">
        <div class="des">
          <span>H&M</span>
          <h5>Relaxed Fit Trauser</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$109</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 7 -->
      <div class="pro" 
      data-product-id="15" 
      data-product-title="Classy Duble Pocket" 
      data-product-image="img/Products/n7.jpg" 
      data-product-price="85"
      onclick="window.location.href='sproduct.php?product=Classy%20Duble%20Pocket';">
        <img src="img/Products/n7.jpg" alt="">
        <div class="des">
          <span>Dymond</span>
          <h5>Classy Duble Pocket</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$85</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      <!-- New Arrivals Product Card 8 -->
      <div class="pro" 
      data-product-id="16" 
      data-product-title="Ciba Dresses" 
      data-product-image="img/Products/n8.jpg" 
      data-product-price="58"
      onclick="window.location.href='sproduct.php?product=Ciba%20Dresses';">
        <img src="img/Products/n8.jpg" alt="">
        <div class="des">
          <span>Curve</span>
          <h5>Ciba Dresses</h5>
          <div>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h4>$58</h4>
        </div>
        <a href="#" class="add-to-cart" onclick="event.stopPropagation();"><i class="fa-solid fa-cart-shopping"></i></a>

      </div>
      </div>
    </div>
  </section>

  <!-- Pagination Section with Fade-In -->
  <section id="pagination" class="section-p1 fade-in">
    <a id="page1-btn" class="active">1</a>
    <a id="page2-btn">2</a>
    <a id="next-btn"><i class="fa-solid fa-arrow-right"></i></a>
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

  <!-- Animated Popup Message Container -->
  <div id="popup-message" class="popup-message"></div>

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

  <!-- Pagination / Sliding Functionality for Shop Page -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const slider = document.getElementById("slider");
      const page1Btn = document.getElementById("page1-btn");
      const page2Btn = document.getElementById("page2-btn");
      const nextBtn = document.getElementById("next-btn");
      
      let currentPage = 1;
      slider.style.transform = "translateX(0)";
      
      function updateSlider(page) {
        if (page === 1) {
          slider.style.transform = "translateX(0)";
          page1Btn.classList.add("active");
          page2Btn.classList.remove("active");
        } else if (page === 2) {
          slider.style.transform = "translateX(-50%)";
          page2Btn.classList.add("active");
          page1Btn.classList.remove("active");
        }
      }
      
      function popButton(btn) {
        btn.classList.add("pop");
        setTimeout(() => {
          btn.classList.remove("pop");
        }, 300);
      }
      
      page1Btn.addEventListener("click", () => {
        currentPage = 1;
        updateSlider(currentPage);
        popButton(page1Btn);
      });
      page2Btn.addEventListener("click", () => {
        currentPage = 2;
        updateSlider(currentPage);
        popButton(page2Btn);
      });
      nextBtn.addEventListener("click", () => {
        currentPage = (currentPage === 1) ? 2 : 1;
        updateSlider(currentPage);
        popButton(nextBtn);
      });
    });
  </script>

  <!-- Search and Cart Functionality -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      
  
      // Popup message functions
      function showPopup(message) {
        const popup = document.getElementById("popup-message");
        popup.textContent = message;
        popup.style.background = "#4caf50";
        popup.classList.add("active");
        setTimeout(() => {
          popup.classList.remove("active");
        }, 2000);
      }
  
      function showLoginPopup(message) {
        const popup = document.getElementById("popup-message");
        popup.textContent = message;
        popup.style.background = "#e74c3c";
        popup.classList.add("active");
        setTimeout(() => {
          popup.classList.remove("active");
          popup.style.background = "#4caf50";
        }, 2000);
      }
  
      // Search functionality
      const searchBtn = document.getElementById("search-btn");
      const searchForm = document.getElementById("search-form");
      searchBtn.addEventListener("click", e => {
        e.preventDefault();
        searchForm.classList.toggle("active");
      });
      document.addEventListener("click", e => {
        if (!searchForm.contains(e.target) && !searchBtn.contains(e.target)) {
          searchForm.classList.remove("active");
        }
      });
  
      const searchInput = document.querySelector("#search-form input");
      const suggestionsContainer = document.getElementById("suggestions-container");
      const suggestionsList = [
        { name: "Cartoon Astronaut Shirts", type: "product" },
        { name: "Floral Print Shirts", type: "product" },
        { name: "Ornament Print Shirts", type: "product" },
        { name: "Paisley Print Shirts", type: "product" },
        { name: "Abstract Print Shirts", type: "product" },
        { name: "Boxy Fit Shirts", type: "product" },
        { name: "Classy Curve Pants", type: "product" },
        { name: "Ciba Dresses", type: "product" },
        { name: "Zara", type: "brand" },
        { name: "Raymond", type: "brand" },
        { name: "H&M", type: "brand" },
        { name: "Ajio", type: "brand" },
        { name: "Denim", type: "brand" }
      ];
      searchInput.addEventListener("input", function () {
        const query = this.value.toLowerCase();
        suggestionsContainer.innerHTML = "";
        if (query.length === 0) {
          suggestionsContainer.style.display = "none";
          return;
        }
        const filteredSuggestions = suggestionsList.filter(item =>
          item.name.toLowerCase().includes(query)
        );
        if (filteredSuggestions.length === 0) {
          const noResult = document.createElement("div");
          noResult.textContent = "No items found";
          noResult.style.cursor = "default";
          suggestionsContainer.appendChild(noResult);
          suggestionsContainer.style.display = "block";
          return;
        }
        filteredSuggestions.forEach(item => {
          const suggestionItem = document.createElement("div");
          suggestionItem.textContent = item.name;
          suggestionItem.addEventListener("click", () => {
            searchInput.value = item.name;
            suggestionsContainer.style.display = "none";
            if (item.type === "product") {
              window.location.href = "sproduct.php?product=" + encodeURIComponent(item.name);
            } else {
              window.location.href = "shop.php?search=" + encodeURIComponent(item.name);
            }
          });
          suggestionsContainer.appendChild(suggestionItem);
        });
        suggestionsContainer.style.display = "block";
      });
      searchInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
          e.preventDefault();
          const query = this.value;
          suggestionsContainer.style.display = "none";
          window.location.href = "shop.php?search=" + encodeURIComponent(query);
        }
      });
      const searchButton = document.querySelector("#search-form button");
      searchButton.addEventListener("click", (e) => {
        e.preventDefault();
        const query = searchInput.value;
        suggestionsContainer.style.display = "none";
        window.location.href = "shop.php?search=" + encodeURIComponent(query);
      });
  
      // Add-to-cart functionality
const addToCartButtons = document.querySelectorAll(".add-to-cart");
addToCartButtons.forEach(button => {
  button.addEventListener("click", e => {
    e.stopPropagation();
    e.preventDefault();
    
    // Check if the user is logged in (assume "loggedIn" is defined via PHP)
    if (!loggedIn) {
        showLoginPopup("Please log in first to add items to your cart!");
        window.location.href = "signin.php";
        return;
      }
      
      // Get the closest product card and retrieve its data attributes
      const productCard = e.target.closest(".pro");
      const productId = productCard.getAttribute("data-product-id");
      const productTitle = productCard.getAttribute("data-product-title");
      const productImage = productCard.getAttribute("data-product-image");
      const productPrice = productCard.getAttribute("data-product-price");
      const quantity = 1; // Default quantity; adjust if needed
      
      // Create form data to send via AJAX
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
        // Optionally, handle the response (e.g., show a popup or update cart count)
        showPopup("Item added to cart!");
        // Optionally update a cart count element here
      })
      .catch(error => {
      console.error("Error:", error);
      showPopup("Failed to add item to cart!");
    });
    });
  });

    });
  </script>
  <script type="module" src="/main.js"></script>
</body>
</html>