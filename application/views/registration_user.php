<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FoodsOUT | Create an Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fff;
    }

    .register-container {
      display: flex;
      height: 100vh;
      /* make left image 1/3 and form 2/3 on wide screens */
      gap: 0;
    }

    /* left = 33.33% , right = 66.66% */
    .register-left {
      flex: 0 0 33.333%;
      max-width: 33.333%;
      background: url('<?php echo base_url("assets/img/login_signup.jpg"); ?>') center center/cover no-repeat;
      border-right: 2px solid #e3e3e3;
    }

    .register-right {
      flex: 0 0 66.666%;
      max-width: 66.666%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #fff;
    }

    .register-box {
      width: 100%;
      max-width: 500px;
      padding: 2rem;
    }

    .register-box h2 {
      font-weight: bold;
      text-align: center;
      color: #000;
      margin-bottom: 0.25rem;
    }

    .register-box p {
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 1rem;
      color: #333;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #666;
      margin: 1.2rem 0;
    }

    .divider::before, .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #ccc;
    }

    .divider:not(:empty)::before {
      margin-right: .75em;
    }

    .divider:not(:empty)::after {
      margin-left: .75em;
    }

    .form-control {
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
    }

    .btn-primary {
      border-radius: 0.75rem;
      padding: 0.75rem;
      font-weight: bold;
      background: linear-gradient(90deg, #007bff, #6610f2);
      border: none;
      transition: 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #0056b3, #520dc2);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .social-login button {
      border: none;
      background: none;
      margin: 0 5px;
      font-size: 1.8rem;
    }

    .social-login button i {
      color: #007bff;
      transition: 0.3s;
    }

    .social-login button:hover i {
      color: #520dc2;
      transform: scale(1.1);
    }

    .bottom-text {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.95rem;
    }

    .bottom-text a {
      color: #007bff;
      text-decoration: none;
      font-weight: 500;
    }

    .bottom-text a:hover {
      text-decoration: underline;
    }

    .admin-btn {
      position: fixed;
      bottom: 10px;
      right: 10px;
      font-size: 0.85rem;
      border: 1px solid #999;
      background: #fff;
      padding: 5px 10px;
      border-radius: 0.5rem;
      transition: 0.3s;
    }

    .admin-btn:hover {
      background: #f1f1f1;
    }

    @media (max-width: 768px) {
      .register-container {
        flex-direction: column;
      }
      .register-left {
        height: 200px;
        max-width: 100%;
        flex: 0 0 auto;
        border-right: none;
        border-bottom: 2px solid #e3e3e3;
      }
      .register-right {
        max-width: 100%;
        flex: 0 0 auto;
      }
    }
  </style>
  <style>
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
              <li><a class="dropdown-item" href="#">My Orders</a></li>
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

  <div class="register-container">
    <!-- Left Image Section -->
    <div class="register-left"></div>

    <!-- Right Form Section -->
    <div class="register-right">
      <div class="register-box">
        <a class="navbar-brand fw-bold" href="<?php echo site_url('PagesController/index'); ?>"><h2>FOODSOUT</h2></a>
        <p>Create an Account</p>
        <div class="divider">Sign Up</div>

        <?php 
        // Set error delimiters for red text
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>'); 
        ?>

        <!-- FORM -->
        <form action="<?php echo site_url('PagesController/register_validation'); ?>" method="post">
          <div class="row">

          <!-- FIRST NAME -->
            <div class="col-md-6 mb-3">
              <label for="first_name" class="form-label">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>" >
              <?php echo form_error('first_name'); ?>
            </div>

          <!-- LAST NAME -->
            <div class="col-md-6 mb-3">
              <label for="last_name" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>">
              <?php echo form_error('last_name'); ?>
            </div>
          </div>

          <!-- USERNAME -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>">
              <?php echo form_error('username'); ?>
            </div>
            <div class="col-md-6 mb-3">
              <label for="birth_date" class="form-label">Birthdate</label>
              <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo set_value('birth_date'); ?>">
              <?php echo form_error('birth_date'); ?>
            </div>
          </div>

          <!-- EMAIL ADDRESS -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email_address" class="form-label">Email Address</label>
              <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>">
              <?php echo form_error('email_address'); ?>
            </div>

          <!-- PHONE NUMBER -->
            <div class="col-md-6 mb-3">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo set_value('phone_number'); ?>">
              <?php echo form_error('phone_number'); ?>
            </div>
          </div>


          <!-- PASSWORD -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
              <?php echo form_error('password'); ?>
            </div>

          <!-- CONFIRM PASSWORD -->
            <div class="col-md-6 mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password">
              <?php echo form_error('confirm_password'); ?>
            </div>
          </div>


          <!-- TERMS AND PRIVACY -->
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="terms" >
            <label class="form-check-label" for="terms">I have read and agree to the privacy policy, terms of service, and community guidelines.</label>
          </div>

          <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>

        <div class="divider">Continue with</div>
        <div class="text-center social-login">
          <button><i class="fab fa-facebook"></i></button>
          <button><i class="fab fa-google"></i></button>
        </div>

        <div class="bottom-text">
          Already have an account? <a href="<?php echo site_url('PagesController/login_user'); ?>">Sign in</a>
        </div>
      </div>
    </div>
  </div>

  <a href="<?php echo site_url('PagesController/login_admin'); ?>" class="admin-btn">Sign in as Admin?</a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
