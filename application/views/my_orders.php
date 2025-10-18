<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Orders - FoodsOUT</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .order-card { border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.06); margin-bottom:18px; padding:16px; }
    .item-row { border-top:1px solid #f1f1f1; padding-top:12px; margin-top:12px; }
    .status-badge { font-weight:700; padding:.45rem .6rem; border-radius:.375rem; }
    .btn-cancel { background:#D22525; color:#fff; border-radius:0; border:none; }
    .btn-cancel:hover { background:#FFB936; color:#000; }
  </style>
  <style>

        /* make cart icon black (Bootstrap Icons) */
    .navbar .nav-link .bi-cart-fill,
    .navbar .nav-link .bi-cart {
      color: #000 !important;
    }

    /* ensure navbar sits above page content and dropdown is interactive */
    .navbar { z-index: 1100; }

    /* center-nav is absolute and may overlap the right-side controls.
       Make it ignore pointer events except for its own links so clicks pass through. */
    .navbar .center-nav {
      pointer-events: none;
      z-index: 1;
    }
    .navbar .center-nav .nav-link {
      pointer-events: auto; /* links still clickable */
    }

    /* make right-side nav (user/cart) and dropdown above the center-nav */
    .navbar .navbar-nav.align-items-center {
      z-index: 1200;
    }

    /* ensure dropdown menu appears on top */
    .dropdown-menu {
      z-index: 2000;
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

        /* make cart icon black (Bootstrap Icons) */
    .navbar .nav-link .bi-cart-fill,
    .navbar .nav-link .bi-cart {
      color: #000 !important;
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

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mt-5">Placed Orders</h2>
    </div>

    <?php if (empty($orders)): ?>
      <div class="alert alert-info">You have no placed orders yet.</div>
    <?php else: ?>

      <?php foreach ($orders as $order):
        // expected fields: id, order_number, status, total_amount, created_at, items (array)
        $s = strtolower($order['status'] ?? '');
        $canCancel = in_array($s, ['pending','processing']);
      ?>
        <div class="order-card"
             id="order-card-<?php echo (int)$order['id']; ?>"
             data-order-id="<?php echo (int)$order['id']; ?>"
             data-order-json="<?php echo htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8'); ?>">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="mb-1">Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></h5>
              <small class="text-muted">Placed: <?php echo htmlspecialchars($order['created_at'] ?? ''); ?></small>
            </div>

            <div class="text-end">
              <div>
                <span class="status-badge <?php
                  if ($s === 'pending') echo 'bg-warning text-dark';
                  elseif ($s === 'processing') echo 'bg-info text-dark';
                  elseif ($s === 'completed') echo 'bg-success text-white';
                  elseif ($s === 'cancelled' || $s === 'canceled') echo 'bg-secondary text-white';
                  else echo 'bg-light text-dark';
                ?>"><?php echo htmlspecialchars($order['status'] ?? ''); ?></span>
              </div>
              <div class="mt-2">
                <strong class="fs-5 text-danger">₱<?php echo number_format((float)($order['total_amount'] ?? 0),2); ?></strong>
              </div>
            </div>
          </div>

          <div class="item-row">
            <?php if (!empty($order['items']) && is_array($order['items'])): ?>
              <?php foreach ($order['items'] as $it): ?>
                <div class="d-flex justify-content-between align-items-center py-2">
                  <div>
                    <div class="fw-semibold"><?php echo htmlspecialchars($it['name'] ?? ''); ?></div>
                    <small class="text-muted">Qty: <?php echo (int)($it['qty'] ?? 1); ?> • ₱<?php echo number_format((float)($it['price'] ?? 0),2); ?></small>
                  </div>
                  <div class="text-end">
                    <small class="text-muted">Subtotal</small>
                    <div>₱<?php echo number_format(((float)($it['price'] ?? 0) * (int)($it['qty'] ?? 1)),2); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-muted">No items available for this order.</div>
            <?php endif; ?>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
              <small class="text-muted">Payment: <?php echo htmlspecialchars($order['payment_status'] ?? 'pending'); ?></small>
            </div>
            <div>
              <button type="button" class="btn btn-outline-secondary btn-sm btn-details me-2" data-order-id="<?php echo (int)$order['id']; ?>">
                View Details
              </button>

              <button
                class="btn btn-cancel btn-sm"
                data-order-id="<?php echo (int)$order['id']; ?>"
                <?php if (!$canCancel) echo 'disabled title="This order cannot be cancelled"'; ?>
                >
                Cancel Order
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    <?php endif; ?>
  </div>

  <!-- Confirm Cancel Modal -->
  <div class="modal fade" id="cancelConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center p-4">
          <h5 class="mb-3">Cancel Order</h5>
          <p class="text-muted">Are you sure you want to cancel this order? This action cannot be undone.</p>
          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, keep order</button>
            <button type="button" id="confirmCancelBtn" class="btn btn-danger">Yes, cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Result Modal -->
  <div class="modal fade" id="cancelResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content text-center p-3">
        <div class="modal-body">
          <div id="cancelResultMsg" class="mb-2"></div>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Details Modal -->
  <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="od_header" class="mb-3"></div>
          <div id="od_items"></div>
          <div id="od_summary" class="mt-3"></div>
          <div id="od_files" class="mt-3"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      var selectedOrderId = null;
      var cancelModal = new bootstrap.Modal(document.getElementById('cancelConfirmModal'));
      var resultModal = new bootstrap.Modal(document.getElementById('cancelResultModal'));
      var confirmBtn = document.getElementById('confirmCancelBtn');

      document.querySelectorAll('.btn-cancel').forEach(function(btn){
        btn.addEventListener('click', function(){
          selectedOrderId = this.dataset.orderId;
          cancelModal.show();
        });
      });

      confirmBtn.addEventListener('click', async function(){
        if (!selectedOrderId) return;
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Cancelling...';

        var fd = new FormData();
        fd.append('order_id', selectedOrderId);

        try {
          var resp = await fetch('<?php echo site_url('PagesController/cancel_order'); ?>', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With':'XMLHttpRequest' }
          });

          var text = await resp.text();

          if (resp.ok) {
            // update UI
            var btn = document.querySelector('.btn-cancel[data-order-id="'+selectedOrderId+'"]');
            if (btn) {
              btn.disabled = true;
              var card = btn.closest('[id^="order-card-"]');
              if (card) {
                var badge = card.querySelector('.status-badge');
                if (badge) {
                  badge.textContent = 'Cancelled';
                  badge.className = 'status-badge bg-secondary text-white';
                }
              }
            }

            cancelModal.hide();
            document.getElementById('cancelResultMsg').innerText = 'Order cancelled successfully.';
            resultModal.show();
          } else {
            cancelModal.hide();
            document.getElementById('cancelResultMsg').innerText = 'Unable to cancel order. ' + (text || '');
            resultModal.show();
          }
        } catch (err) {
          cancelModal.hide();
          document.getElementById('cancelResultMsg').innerText = 'Network error. Please try again.';
          resultModal.show();
        } finally {
          confirmBtn.disabled = false;
          confirmBtn.textContent = 'Yes, cancel';
          selectedOrderId = null;
        }
      });

      // Order details
      var detailsModalEl = document.getElementById('orderDetailsModal');
      var detailsModal = new bootstrap.Modal(detailsModalEl);

      document.querySelectorAll('.btn-details').forEach(function(btn){
        btn.addEventListener('click', function(){
          var id = this.dataset.orderId;
          var card = document.querySelector('[data-order-id="'+id+'"]');
          if (!card) return;
          var json = card.dataset.orderJson || '{}';
          try {
            var order = JSON.parse(json);
          } catch (e) {
            order = {};
          }

          // header
          var headerHtml = '<h6 class="mb-1">Order #' + (order.order_number || order.id || '') + '</h6>';
          headerHtml += '<small class="text-muted">Placed: ' + (order.created_at || '') + '</small>';
          document.getElementById('od_header').innerHTML = headerHtml;

          // items
          var itemsHtml = '';
          if (Array.isArray(order.items) && order.items.length) {
            itemsHtml += '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Item</th><th>Qty</th><th class="text-end">Price</th></tr></thead><tbody>';
            order.items.forEach(function(it){
              itemsHtml += '<tr><td>' + (it.name || '') + '</td><td>' + (it.qty||1) + '</td><td class="text-end">₱' + (Number(it.price||0).toFixed(2)) + '</td></tr>';
            });
            itemsHtml += '</tbody></table></div>';
          } else {
            itemsHtml = '<div class="text-muted">No items available.</div>';
          }
          document.getElementById('od_items').innerHTML = itemsHtml;

          // summary
          var summaryHtml = '<table class="table table-borderless table-sm"><tbody>';
          summaryHtml += '<tr><td><strong>Total</strong></td><td class="text-end">₱' + (Number(order.total_amount||0).toFixed(2)) + '</td></tr>';
          summaryHtml += '<tr><td>Payment Method</td><td class="text-end">' + (order.payment_method || '-') + '</td></tr>';
          summaryHtml += '<tr><td>Payment Status</td><td class="text-end">' + (order.payment_status || '-') + '</td></tr>';
          summaryHtml += '<tr><td>Status</td><td class="text-end">' + (order.status || '-') + '</td></tr>';
          summaryHtml += '</tbody></table>';
          document.getElementById('od_summary').innerHTML = summaryHtml;

          // files (if any property contains filenames; optional)
          var filesHtml = '';
          if (order.files && Array.isArray(order.files) && order.files.length) {
            filesHtml += '<div><strong>Files:</strong><ul>';
            order.files.forEach(function(f){
              filesHtml += '<li>' + f + '</li>';
            });
            filesHtml += '</ul></div>';
          } else {
            filesHtml = '';
          }
          document.getElementById('od_files').innerHTML = filesHtml;

          detailsModal.show();
        });
      });
    })();

    
  </script>

  
</body>
</html>