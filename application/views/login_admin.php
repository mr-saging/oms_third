<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FoodsOUT Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fff;
    }

    .login-container {
      display: flex;
      height: 100vh;
    }

    /* left image 1/3, right form 2/3 */
    .login-left {
      flex: 0 0 33.333%;
      max-width: 33.333%;
      background: url('<?php echo base_url("assets/img/admin_login.jpg"); ?>') center center/cover no-repeat;
    }

    .login-right {
      flex: 0 0 66.666%;
      max-width: 66.666%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #fff;
    }

    .login-box {
      width: 100%;
      max-width: 380px;
      padding: 2rem;
    }

    .login-box h2 {
      font-weight: bold;
      text-align: center;
      margin-bottom: 0.5rem;
      color: #222;
    }

    .login-box h5 {
      text-align: center;
      color: #333;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
    }

    .form-control.is-invalid {
      border-color: #dc3545;
    }

    .text-danger {
      font-size: 0.875rem;
    }

    .btn-primary {
      border-radius: 0.75rem;
      padding: 0.75rem;
      font-weight: bold;
      background: linear-gradient(90deg, #007bff, #6610f2);
      border: none;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #0056b3, #520dc2);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #555;
    }

    .back-btn {
      margin-top: 1rem;
      display: block;
      text-align: center;
      font-size: 0.9rem;
      color: #007bff;
      text-decoration: none;
      transition: 0.3s;
    }

    .back-btn:hover {
      text-decoration: underline;
      color: #0056b3;
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }
      .login-left {
        height: 200px;
        max-width: 100%;
        flex: 0 0 auto;
      }
      .login-right {
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


  <div class="login-container">
    <div class="login-left"></div>

    <div class="login-right">
      <div class="login-box">
        <h2>FOODSOUT</h2>
        <h5>Admin</h5>
        <hr>

        <!-- Display flashdata error from login attempt -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form action="<?php echo site_url('PagesController/admin_login_validation'); ?>" method="post">

          <div class="mb-3">
            <label for="username" class="form-label">Username or Email Address</label>
            <input type="text" class="form-control <?php echo (form_error('username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo set_value('username'); ?>" >
            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control <?php echo (form_error('password')) ? 'is-invalid' : ''; ?>" id="password" name="password" >
            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
          </div>

          <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>

        <a href="<?php echo site_url('PagesController/login_user'); ?>" class="back-btn">
          ← Back to User Login
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
