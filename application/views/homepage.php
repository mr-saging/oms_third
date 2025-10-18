<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodsOUT | Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    /* --- FIXED HEADER / NAVBAR --- */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      color: #ff4d00 !important;
      font-size: 1.5rem;
    }

    /* centered nav (dynamic) */
    .navbar .center-nav {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      display: flex;
      gap: 1rem;
    }

    /* ensure collapse works on smaller screens */
    @media (max-width: 991.98px) {
      .navbar .center-nav {
        position: static;
        transform: none;
        margin: 0 auto;
        flex-direction: column;
        gap: 0.25rem;
      }
    }

    .navbar-nav .nav-link {
      color: #333 !important;
      font-weight: 500;
      margin-right: 10px;
      transition: color 0.3s ease;
    }

    /* make cart icon black (Bootstrap Icons) */
    .navbar .nav-link .bi-cart-fill,
    .navbar .nav-link .bi-cart {
      color: #000 !important;
    }

    .navbar-nav .nav-link:hover {
      color: #ff4d00 !important;
    }

    .btn-login {
      border: 2px solid #ff4d00;
      color: white;
      background-color: #ff4d00;
      border-radius: 50px;
      padding: 6px 18px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background-color: #fff;
      color: #ff4d00;
    }

    /* --- FULLSCREEN CAROUSEL --- */
    .carousel-item img {
      height: 100vh;
      width: 100%;
      object-fit: cover;
    }

    .carousel-caption {
      bottom: 20%;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
    }

    .carousel-caption h5 {
      font-size: 3rem;
      font-weight: 800;
      color: #fff;
    }

    .carousel-caption p {
      font-size: 1.3rem;
      color: #f1f1f1;
    }

    /* smoother fade transition for carousel */
    /* improved cross-fade (prevents white flash between slides) */
    .carousel,
    .carousel-inner,
    .carousel-item {
      background: #000; /* fallback background while images cross-fade */
    }

    .carousel-fade .carousel-item {
      opacity: 0;
      transition: opacity 1s ease-in-out;
      backface-visibility: hidden;
      transform: none;
    }

    /* Make the active/coming items visible during the cross-fade */
    .carousel-fade .carousel-item.active,
    .carousel-fade .carousel-item.carousel-item-start,
    .carousel-fade .carousel-item.carousel-item-end,
    .carousel-fade .carousel-item.carousel-item-next,
    .carousel-fade .carousel-item.carousel-item-prev {
      opacity: 1;
    }

    /* Ensure transforms are not applied (prevents slide motion) */
    .carousel-fade .carousel-item-next,
    .carousel-fade .carousel-item-prev,
    .carousel-fade .carousel-item.active {
      transform: translateX(0);
    }

    /* make images block-level and full-bleed to avoid gaps */
    .carousel-item img {
      display: block;
      width: 100%;
      height: 100vh;
      object-fit: cover;
    }

    /* --- ABOUT SECTION --- */
    .about-section {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 60px;
      padding: 120px 10%;
      background: linear-gradient(135deg, #fff, #f7f7f7);
    }

    .about-section img {
      width: 350px;
      height: auto;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      transition: transform 0.4s ease;
    }

    .about-section img:hover {
      transform: scale(1.05);
    }

    .about-text {
      max-width: 600px;
    }

    .about-text h2 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #000;
    }

    .about-text p {
      font-size: 1.2rem;
      color: #444;
      line-height: 1.7;
      margin-bottom: 25px;
    }

    .about-text button {
      /* replaced by .btn-see-more styling below */
    }

    /* SEE MORE button: rectangular, D22525 base, FFB936 on hover */
    .about-text .btn-see-more {
      border: none;
      background: #D22525;
      color: #ffffff;
      padding: 12px 30px;
      border-radius: 0;
      font-weight: 600;
      letter-spacing: 0.2px;
      transition: background 0.25s ease, transform 0.2s ease;
      cursor: pointer;
      display: inline-block;
      text-decoration: none;
    }

    .about-text .btn-see-more:hover,
    .about-text .btn-see-more:focus {
      background: #FFB936;
      color: #000;
      transform: translateY(-2px);
    }

    /* --- PRODUCT CARDS --- */
    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 15px;
      overflow: hidden;
      padding: 0; /* ensure inner spacing handled by .product-image */
      background: #fff;
    }

    /* image wrapper to create even spacing at four corners */
    .product-image {
      padding: 14px;              /* "margin on four corners" inside the card */
      background: transparent;
      display: block;
    }
    .product-image img {
      width: 100%;
      height: 320px;             /* taller images */
      object-fit: cover;
      display: block;
      border-radius: 12px;       /* soft corner on the image itself */
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    /* card body adjustments */
    .card-body {
      text-align: center;
      padding: 16px;
    }

    /* price styling: larger and D22525 color */
    .product-price {
      color: #D22525;
      font-weight: 800;
      font-size: 1.25rem;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .card-text {
      font-size: 1rem;
      color: #444;
    }

    .btn-primary {
      border-radius: 20px;
      padding: 6px 18px;
    }

    /* Add to Cart: rectangular red button, hover amber */
    .btn-add-cart {
      display: inline-block;
      background: #D22525;
      color: #ffffff;
      border: none;
      padding: 8px 18px;
      border-radius: 0 !important;
      font-weight: 600;
      letter-spacing: 0.2px;
      cursor: pointer;
      transition: background 0.22s ease, transform 0.12s ease;
      text-decoration: none;
    }
    .btn-add-cart:hover,
    .btn-add-cart:focus {
      background: #FFB936;
      color: #000;
      transform: translateY(-2px);
    }

    /* --- OFFER SECTION --- */
    .offer-section { text-align: center; }

    /* Split offer into two rows: top = white, bottom = D22525 */
    .offer-top {
      background: #FFFFFF;
      /* ensure the top section fully contains the video and sits above the red row */
      padding: 80px 20px 32px; /* give enough bottom space so video isn't visually overlapped */
      position: relative;
      z-index: 2;
    }
    .offer-top h2 {
      font-weight: 700;
      margin-bottom: 30px;
      color: #000;
    }

    .offer-bottom {
      background: #D22525;
      /* make sure this row does not float over the top row */
      position: relative;
      z-index: 1;
      padding: 40px 20px 70px;
      color: #fff; /* ensure text is readable on the red background */
    }
    .offer-bottom .offer-icons { justify-content: center; }
    .offer-bottom .offer-item p { color: rgba(255,255,255,0.95); }

    .offer-video {
      width: 80%;
      max-width: 800px;
      height: 400px;
      border: 3px solid #000;
      margin: 0 auto; /* removed bottom margin that caused visual overlap */
      position: relative;
      border-radius: 15px;
      background-color: #fafafa;
      overflow: hidden; /* ensure thumbnail fits */
      z-index: 3; /* keep the video visually on top of the two-row background */
    }

    /* thumbnail inside video area */
    .offer-video .offer-video-thumb {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .offer-video .video-link { display:block; width:100%; height:100%; text-decoration:none; color:inherit; }

    .offer-video .play-button {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 80px;
      color: rgba(255,255,255,0.95);
      cursor: pointer;
      transition: transform 0.2s ease;
      text-shadow: 0 6px 20px rgba(0,0,0,0.6);
      pointer-events: none; /* allow the anchor click through the overlay */
    }

    .offer-video:hover .play-button { transform: translate(-50%, -50%) scale(1.05); }

    .offer-icons {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 50px;
    }

    .offer-item {
      text-align: center;
      width: 150px;
    }

    .offer-item img {
      width: 90px;
      height: 90px;
      object-fit: contain;
      margin-bottom: 10px;
      transition: transform 0.3s ease;
    }

    .offer-item img:hover {
      transform: scale(1.1);
    }

    /* --- REVIEWS --- */
    .reviews-section {
      background-color: #f8f9fa;
      padding: 80px 0;
    }

    .review-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #000;
    }

    .bi-quote {
      font-size: 1.5rem;
      color: #000;
    }

    /* --- NEWSLETTER --- */
    .newsletter-section {
      background-color: #fff;
      padding: 80px 0;
    }

    .newsletter-section h2 {
      font-size: 2.2rem;
      font-weight: 700;
    }

    .newsletter-section .btn {
      border-radius: 0;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* --- Responsive --- */
    @media (max-width: 768px) {
      .about-section {
        flex-direction: column;
        text-align: center;
      }

      .about-section img {
        width: 250px;
      }

      .about-text h2 {
        font-size: 2rem;
      }

      .carousel-caption h5 {
        font-size: 1.8rem;
      }

      .carousel-caption p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>

<!-- ✅ FIXED / STICKY NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
  <div class="container">
    <!-- Brand -->
    <a class="navbar-brand fw-bold" href="<?php echo site_url('PagesController/index'); ?>">FoodsOUT</a>

    <!-- Navbar Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">

      <!-- ✅ CENTER LINKS -->
      <ul class="navbar-nav center-nav fw-semibold">
        <li class="nav-item">
          <a class="nav-link" href="#about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#featured">Featured</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#offers">Offers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#more">More</a>
        </li>
      </ul>

      <!-- ✅ RIGHT SIDE: USER / CART -->
      <ul class="navbar-nav align-items-center ms-auto">

      <!-- LOGGED IN SESSION -->
        <?php if ($this->session->userdata('username')): ?>
          <!-- Browse button (visible only when user logged in) -->
          <li class="nav-item me-2">
            <a class="btn btn-outline-dark btn-sm" href="<?php echo site_url('PagesController/food_menu'); ?>">Browse</a>
          </li>

          <!-- Shopping Cart Icon (uses Bootstrap Icons; forced black) -->
          <li class="nav-item me-3">
            <a class="nav-link position-relative" href="<?php echo site_url('PagesController/food_cart'); ?>">
              <i class="bi bi-cart-fill text-dark" style="font-size:1.2rem;"></i>

              <!-- Optional cart item count -->
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
              </span>
            </a>
          </li>

          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle text-dark"></i>
              <?php echo htmlspecialchars($this->session->userdata('username')); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="<?php echo base_url('PagesController/my_orders')?>">My Orders</a></li>
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?php echo site_url('PagesController/logout'); ?>">Logout</a></li>
            </ul>
          </li>

        <?php else: ?>

          <!-- LOGGED OUT SESSION -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('PagesController/login_user'); ?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary ms-2" href="<?php echo site_url('PagesController/registration_user'); ?>">Sign Up</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>


<!-- ✅ FULLSCREEN CAROUSEL -->
<div id="foodCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="<?php echo base_url('assets/img/slideshow_pizza.png'); ?>" alt="Pizza">
      <div class="carousel-caption">

      </div>
    </div>

    <div class="carousel-item">
      <img src="<?php echo base_url('assets/img/slideshow_offers.png'); ?>" alt="Burger">
      <div class="carousel-caption">

      </div>
    </div>

    <div class="carousel-item">
      <img src="<?php echo base_url('assets/img/slideshow_pasta.png'); ?>" alt="Pasta">
      <div class="carousel-caption">
      </div>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#foodCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#foodCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- ✅ ABOUT SECTION -->
<section class="about-section">
  <img src="<?php echo base_url('assets/img/foodsout_logo.png'); ?>" alt="FoodsOUT Logo">
  <div class="about-text">
    <h2>About FoodsOUT</h2>
    <p>
      <strong>FoodsOUT</strong> is an innovative Order Management System (OMS) designed to simplify and automate food delivery operations. 
      Whether you're a small eatery or a large restaurant chain, FoodsOUT helps keep your operations organized and your customers satisfied.
    </p>
    <button class="btn-see-more">SEE MORE...</button>
  </div>
</section>

<!-- ✅ FEATURED PRODUCTS -->
<div class="container py-5">
  <h1 class="text-center mb-4">Featured Products</h1>
  <div class="row g-4">
    <?php
      $products = [
        ['img' => 'burger2.jpg', 'name' => 'Hot Burger', 'price' => '₱95.00'],
        ['img' => 'salad.jpg', 'name' => 'Caesar Salad', 'price' => '₱100.00'],
        ['img' => 'chicken2.jpg', 'name' => 'Grilled Chicken', 'price' => '₱180.00'],
        ['img' => 'steak.jpg', 'name' => 'Steak', 'price' => '₱250.00'],
        ['img' => 'fish.jpg', 'name' => 'Cooked Fish', 'price' => '₱200.00'],
        ['img' => 'fishandchips.jpg', 'name' => 'Fish and Chips', 'price' => '₱220.00']
      ];
      foreach ($products as $p): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card product-card">
          <div class="product-image">
            <img src="<?php echo base_url('assets/img/' . $p['img']); ?>" alt="<?php echo $p['name']; ?>">
          </div>
          <div class="card-body">
            <h5 class="card-title"><?php echo $p['name']; ?></h5>
            <p class="product-price"><?php echo $p['price']; ?></p>
            <button class="btn btn-add-cart">Add to Cart</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ✅ WHAT WE OFFER SECTION -->
<section class="offer-section">
  <!-- TOP ROW: white background with video -->
  <div class="offer-top">
    <h2>WHAT WE OFFER</h2>

    <!-- youtube thumbnail + play button + external link -->
    <div class="offer-video">
      <a class="video-link" href="https://www.youtube.com/watch?v=I_C9aFyL_qg" target="_blank" rel="noopener noreferrer" aria-label="Watch FoodsOUT video on YouTube">
        <img class="offer-video-thumb" src="https://img.youtube.com/vi/I_C9aFyL_qg/maxresdefault.jpg" alt="FoodsOUT Video Thumbnail">
        <span class="play-button" aria-hidden="true">&#9658;</span>
      </a>
    </div>
  </div>

  <!-- BOTTOM ROW: red background with three offer items -->
  <div class="offer-bottom">
    <div class="offer-icons">
      <div class="offer-item">
        <img src="<?php echo base_url('assets/img/offer_1.png'); ?>" alt="Fast Delivery">
        <p><strong>Fast Delivery</strong></p>
        <p>Quick and reliable delivery to satisfy your cravings on time.</p>
      </div>

      <div class="offer-item">
        <img src="<?php echo base_url('assets/img/offer_2.png'); ?>" alt="Money Back Guarantee">
        <p><strong>Money Back Guarantee</strong></p>
        <p>Not satisfied? We’ll make it right or refund you.</p>
      </div>

      <div class="offer-item">
        <img src="<?php echo base_url('assets/img/offer_3.png'); ?>" alt="Food Freshness">
        <p><strong>Food Freshness</strong></p>
        <p>Every meal is made fresh daily — no compromises.</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ REVIEWS SECTION -->
<section class="reviews-section text-center">
  <div class="container">
    <h2 class="fw-bold mb-5">WHAT OUR CUSTOMERS SAY</h2>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <img src="<?php echo base_url('assets/img/review_1.png'); ?>" class="review-img mb-3">
        <h5><i class="bi bi-quote"></i> Yummy</h5>
        <p class="review-text">"FoodsOUT made our food business so much easier! Orders are organized, deliveries are on time, and customers love the smooth service."</p>
        <p class="fw-bold">Michael John</p>
      </div>

      <div class="col-md-4">
        <img src="<?php echo base_url('assets/img/review_2.png'); ?>" class="review-img mb-3">
        <h5><i class="bi bi-quote"></i> Best Prices</h5>
        <p class="review-text">"I've tried other systems, but FoodsOUT beats all the rest by far. Tracking tools help us make better decisions."</p>
        <p class="fw-bold">Mary Jane</p>
      </div>

      <div class="col-md-4">
        <img src="<?php echo base_url('assets/img/review_3.png'); ?>" class="review-img mb-3">
        <h5><i class="bi bi-quote"></i> Fast Delivery</h5>
        <p class="review-text">"From order taking to delivery, everything runs seamlessly with FoodsOUT. Our team saves hours daily."</p>
        <p class="fw-bold">Gwen Stacy</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ NEWSLETTER SECTION -->
<section class="newsletter-section">
  <div class="container border p-5">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <h2>Subscribe To Our Newsletter<br>To Get More Offers</h2>
        <p>Get exclusive promos and discounts delivered to your inbox!</p>
      </div>
      <div class="col-md-6">
        <form>
          <input type="email" class="form-control mb-3" placeholder="Enter Email Address" required>
          <button type="submit" class="btn btn-dark w-100 mb-2">SUBSCRIBE NOW</button>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="privacyCheck" required>
            <label class="form-check-label" for="privacyCheck">I Agree to the Privacy Policy.</label>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ensure autoplay with consistent options (fallback if data attrs ignored)
  document.addEventListener('DOMContentLoaded', function(){
    var el = document.getElementById('foodCarousel');
    if (el && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
      new bootstrap.Carousel(el, {
        interval: 4000,   // 4 seconds per slide
        ride: 'carousel',
        pause: 'hover',   // pause on hover
        touch: true,
        wrap: true
      });
    }
  });
</script>
</body>
</html>
