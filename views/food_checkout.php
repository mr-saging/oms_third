<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - OMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* --- NAVBAR (same as homepage) --- */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }
    .navbar-brand { font-weight: bold; color: #ff4d00 !important; font-size: 1.25rem; }
    .navbar .center-nav { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); display:flex; gap:1rem; }
    @media (max-width: 991.98px) {
      .navbar .center-nav { position: static; transform: none; margin: 0 auto; flex-direction: column; gap: .25rem; }
    }
    .navbar-nav .nav-link { color: #333 !important; font-weight: 500; margin-right: 10px; transition: color 0.3s ease; }
    .navbar .nav-link .bi-cart-fill, .navbar .nav-link .bi-cart { color: #000 !important; }
    .navbar-nav .nav-link:hover { color: #ff4d00 !important; }
    .btn-login { border: 2px solid #ff4d00; color: white; background-color: #ff4d00; border-radius: 50px; padding: 6px 18px; font-weight:500; }
    .btn-login:hover { background-color:#fff; color:#ff4d00; }

    :root{
      --accent:#e94b3c;
      --accent-2:#ff7a00;
      --muted:#fffdf9;
      --card-radius:12px;
      --shadow:0 8px 28px rgba(10,10,10,0.06);
      --btn-green:#27ae60;
    }
    /* ensure page content sits below fixed navbar */
    body { background: linear-gradient(180deg,#fffaf8 0,#fff 100%); font-family: 'Poppins', sans-serif; color:#222; padding:90px 0; }

    .checkout-container { margin: 20px auto; max-width:1100px; }
    .card-box { background: #fff; border-radius: 12px; padding: 22px; box-shadow: var(--shadow); border:1px solid rgba(0,0,0,0.04); }
    .section-title { text-transform:uppercase; font-weight:700; font-size:.78rem; color:#666; letter-spacing:.6px; }
    .btn-pay { background: var(--accent); color:#fff; font-weight:700; border-radius:10px; padding:10px 16px; border:none; }
    .btn-pay:hover { background: #c93a2a; }

    label.form-label { font-weight:600; font-size:.85rem; color:#333; }
    .form-control, .form-select { border-radius:8px; }

    .payment-icons i { font-size:1.25rem; margin-right:10px; color:#6b6b6b; vertical-align:middle; }

    @media (max-width:991.98px){
      .checkout-container { padding: 0 12px; }
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


  <div class="container checkout-container">
    <div class="row g-4 justify-content-center">
      <!-- Full width: Billing & Payment -->
      <div class="col-lg-9">
        <div class="card-box mb-3">
          <h6 class="section-title mb-3">Billing address</h6>

          <?php
            // prefer session values, fallback to $user passed from controller (object or array), else empty
            $first_name = $last_name = $email = $phone = '';
            if ($this->session->userdata('first_name')) {
              $first_name = $this->session->userdata('first_name');
            } elseif (isset($user) && !empty($user->first_name)) {
              $first_name = $user->first_name;
            }
            if ($this->session->userdata('last_name')) {
              $last_name = $this->session->userdata('last_name');
            } elseif (isset($user) && !empty($user->last_name)) {
              $last_name = $user->last_name;
            }
            if ($this->session->userdata('email')) {
              $email = $this->session->userdata('email');
            } elseif (isset($user) && !empty($user->email_address)) {
              $email = $user->email_address;
            }
            if ($this->session->userdata('phone')) {
              $phone = $this->session->userdata('phone');
            } elseif (isset($user) && !empty($user->phone_number)) {
              $phone = $user->phone_number;
            }

            // determine default storeID (first store) for order if available
            $default_store_id = '';
            if (!empty($stores) && is_array($stores)) {
                $firstStore = reset($stores);
                $default_store_id = isset($firstStore->storeID) ? $firstStore->storeID : '';
            }
          ?>

          <!-- Checkout form: posts order summary to PagesController::insert_order -->
          <form id="checkoutForm" action="<?= site_url('PagesController/insert_order') ?>" method="post">
            <!-- user/store/summary hidden fields populated by JS before submit (and userID from session) -->
            <input type="hidden" name="userID" value="<?= (int)$this->session->userdata('user_id') ?>">
            <input type="hidden" name="storeID" id="form_storeID" value="<?= htmlspecialchars($default_store_id) ?>">
            <input type="hidden" name="cart_json" id="form_cart_json" value="">
            <input type="hidden" name="total_amount" id="form_total_amount" value="">
            <!-- default statuses -->
            <input type="hidden" name="status" id="form_status" value="Pending">
            <input type="hidden" name="payment_status" id="form_payment_status" value="Pending">



            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">First name</label>
                <input type="text" class="form-control" name="first_name" placeholder="John"
                  value="<?php echo set_value('first_name', htmlspecialchars($first_name)); ?>">
                <?php echo form_error('first_name'); ?>
              </div>
              <div class="col-md-6">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" name="last_name" placeholder="Doe"
                  value="<?php echo set_value('last_name', htmlspecialchars($last_name)); ?>">
                <?php echo form_error('last_name'); ?>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="you@example.com"
                  value="<?php echo set_value('email', htmlspecialchars($email)); ?>">
                <?php echo form_error('email'); ?>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" placeholder="09xx xxx xxxx"
                  value="<?php echo set_value('phone', htmlspecialchars($phone)); ?>">
                <?php echo form_error('phone'); ?>
              </div>
              <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Street, building, unit"
                  value="<?php echo set_value('address'); ?>">
                <?php echo form_error('address'); ?>
              </div>
              <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="city" placeholder="Manila"
                  value="<?php echo set_value('city'); ?>">
                <?php echo form_error('city'); ?>
              </div>
              <div class="col-md-6">
                <label class="form-label">Postal / ZIP</label>
                <input type="text" class="form-control" name="postal" placeholder="1000"
                  value="<?php echo set_value('postal'); ?>">
                <?php echo form_error('postal'); ?>
              </div>
            </div>
        </div>

        <div class="card-box mb-3">
          <h6 class="section-title mb-3">Payment method</h6>

          <div class="d-flex gap-3 mb-3 align-items-center">
            <div class="payment-icons">
              <i class="bi bi-cash-coin" title="Cash" style="font-size:1.25rem;margin-right:10px;"></i>
              <i class="bi bi-credit-card-2-front-fill" title="Credit Card" style="font-size:1.25rem;"></i>
            </div>
            <div class="text-muted small">Choose payment type</div>
          </div>

          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="pmCash" value="Cash" checked>
              <label class="form-check-label" for="pmCash">Cash</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="pmCard" value="Credit Card">
              <label class="form-check-label" for="pmCard">Credit Card</label>
            </div>
          </div>

          <div id="cardFields" style="display:none;">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Card number</label>
                <input type="text" class="form-control" name="card_number" placeholder="0000 0000 0000 0000" autocomplete="cc-number">
              </div>
              <div class="col-md-6">
                <label class="form-label">Expiry</label>
                <input type="text" class="form-control" name="card_expiry" placeholder="MM / YY" autocomplete="cc-exp">
              </div>
              <div class="col-md-6">
                <label class="form-label">CVC</label>
                <input type="text" class="form-control" name="card_cvc" placeholder="123" autocomplete="cc-csc">
              </div>
              <div class="col-12">
                <label class="form-label">Name on card</label>
                <input type="text" class="form-control" name="card_name" placeholder="John Doe" autocomplete="cc-name">
              </div>
            </div>
          </div>

          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="savePayment">
            <label class="form-check-label" for="savePayment">Save card for next time</label>
          </div>

          <div class="mt-3 d-flex gap-2">
            <a href="<?php echo site_url('PagesController/food_cart'); ?>"><button type="button" class="btn btn-outline-secondary">Cancel</button></a>
            <button type="submit" id="payNowBtn" class="btn-pay ms-auto">Pay now</button>
          </div>

          <script>
            (function(){
              // toggle card fields display
              function toggleCardFields(){
                var cardSelected = document.getElementById('pmCard').checked;
                document.getElementById('cardFields').style.display = cardSelected ? 'block' : 'none';
              }
              document.getElementById('pmCash').addEventListener('change', toggleCardFields);
              document.getElementById('pmCard').addEventListener('change', toggleCardFields);
              toggleCardFields();

              // AJAX submit -> show order complete modal on success
              var form = document.getElementById('checkoutForm');
              var payBtn = document.getElementById('payNowBtn');
              form.addEventListener('submit', async function(e){
                e.preventDefault();

                // gather cart from localStorage
                var CART_KEY = 'foodsout_cart';
                var cart = [];
                try { cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch(err) { cart = []; }

                // fallback to server DOM cart if localStorage empty
                if (!cart || cart.length === 0) {
                  var cartItems = [];
                  document.querySelectorAll('.cart-item').forEach(function(it){
                    var id = it.dataset.id || '';
                    var price = parseFloat(it.dataset.price || 0) || 0;
                    var qtyInput = it.querySelector('.qty-input');
                    var qty = qtyInput ? parseInt(qtyInput.value || 1) : 1;
                    var nameEl = it.querySelector('.item-title');
                    var name = nameEl ? nameEl.textContent.trim() : '';
                    cartItems.push({ id: id, name: name, price: price, qty: qty });
                  });
                  cart = cartItems;
                }

                // compute total
                var total = 0;
                cart.forEach(function(ci){
                  var q = Number(ci.qty) || 1;
                  var p = Number(ci.price) || 0;
                  total += p * q;
                });

                if (total <= 0) {
                  alert('Cart is empty. Add items before paying.');
                  return;
                }

                // set hidden inputs for server
                document.getElementById('form_cart_json').value = JSON.stringify(cart);
                document.getElementById('form_total_amount').value = total.toFixed(2);

                // disable button
                payBtn.disabled = true;
                payBtn.textContent = 'Processing...';

                try {
                  // prepare payload
                  var fd = new FormData(form);
                  // send via fetch; server should accept regular POST
                  var resp = await fetch(form.action, {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                  });

                  // debug: show HTTP status and response body
                  var text = await resp.text();
                  console.log('Order POST status:', resp.status, 'ok:', resp.ok);
                  console.log('Order POST response body:', text);

                  if (resp.ok) {
                    // clear local cart
                    try { localStorage.removeItem(CART_KEY); } catch(e){}
                    var modalEl = document.getElementById('orderCompleteModal');
                    var bsModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
                    bsModal.show();
                  } else {
                    // show server response to help debugging
                    alert('Payment failed or server error. See console for details.\n\nStatus: ' + resp.status + '\n\nResponse snippet: ' + text.slice(0,500));
                    console.error('Server response (full):', text);
                  }
                } catch(err) {
                  console.error(err);
                  alert('Network error. Please try again.');
                } finally {
                  payBtn.disabled = false;
                  payBtn.textContent = 'Pay now';
                }
              });
             })();
          </script>
        </div>
      </form>
      <!-- Order Complete Modal -->
      <div class="modal fade" id="orderCompleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content text-center py-4">
            <div class="modal-body">
              <div style="font-size:72px; color:#27ae60; line-height:1;">
                &#10003;
              </div>
              <h4 class="mt-3">Order Complete</h4>
              <p class="text-muted">Thank you — your order has been placed successfully.</p>
              <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Stay here</button>
                <button type="button" id="continueBrowseBtn" class="btn btn-primary">Continue browsing</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
        // modal buttons
        document.getElementById('continueBrowseBtn').addEventListener('click', function(){
          window.location.href = '<?= site_url('PagesController/index') ?>';
        });
      </script>

        <!-- Optional: compact order summary area inline (minimal) -->
        <?php if (!empty($cart_items)): ?>
          <div class="card-box">
            <h6 class="section-title mb-3">Order summary</h6>
            <?php $subtotal = 0; foreach ($cart_items as $it) { $subtotal += $it['price'] * ($it['qty'] ?? 1); ?>
              <div class="d-flex justify-content-between mb-2">
                <div>
                  <div style="font-weight:700;"><?= htmlspecialchars($it['name']) ?> <span class="text-muted small">x<?= (int)($it['qty'] ?? 1) ?></span></div>
                </div>
                <div>₱<?= number_format($it['price'] * ($it['qty'] ?? 1),2) ?></div>
              </div>
            <?php } ?>
            <div class="d-flex justify-content-between mt-2">
              <div class="text-muted">Subtotal</div>
              <div>₱<?= number_format($subtotal,2) ?></div>
            </div>
            <div class="mt-3 d-flex gap-2">
              <button class="btn btn-outline-secondary">Edit cart</button>
              <button class="btn btn-success ms-auto">Confirm & Pay</button>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
