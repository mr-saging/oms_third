
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>OMS Cart</title>

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
      --accent:#1f8a49;
      --muted:#f7f6f3;
      --card-radius:12px;
      --shadow:0 10px 30px rgba(10,10,10,0.06);
      --danger:#dc3545;
    }

    /* ensure page content sits below fixed navbar */
    body{ background: linear-gradient(180deg,#fbfbfa 0,#fff 100%); font-family: Inter, system-ui, Arial, sans-serif; color:#222; padding:90px 0 30px; }

    .cart-wrap{ max-width:1200px; margin:0 auto; display:flex; gap:24px; align-items:flex-start; padding:0 16px; }
    .cart-list{ flex:1; min-width:0; }
    .cart-card{ background:#fff; border-radius:var(--card-radius); box-shadow:var(--shadow); padding:20px; }
    .cart-item{ display:flex; gap:16px; align-items:center; padding:14px; border-radius:10px; transition:transform .12s, box-shadow .12s; border:1px solid #f1f1f1; }
    .cart-item + .cart-item{ margin-top:12px; }
    .cart-item:hover{ transform:translateY(-4px); box-shadow:0 12px 24px rgba(15,15,15,0.04); }

    .product-img{ width:96px; height:96px; border-radius:10px; object-fit:cover; flex:0 0 96px; }
    .item-meta{ flex:1; min-width:0; }
    .item-title{ font-weight:700; margin:0 0 6px 0; font-size:1rem; }
    .item-desc{ font-size:.88rem; color:#6b6b6b; margin:0 0 8px 0; }
    .price-line{ display:flex; gap:10px; align-items:center; }
    .price{ color:var(--accent); font-weight:700; font-size:1.02rem; }
    .old-price{ text-decoration:line-through; color:#bdbdbd; font-weight:600; font-size:.92rem; }

    .qty-controls{ display:flex; align-items:center; gap:8px; }
    .qty-controls input[type="number"]{ width:72px; text-align:center; border-radius:8px; }
    .btn-ghost{ border:1px solid #e6e6e6; background:transparent; padding:.35rem .6rem; border-radius:8px; }

    /* summary */
    .cart-summary{ width:340px; position:sticky; top:110px; align-self:flex-start; }
    .summary-card{ background:#fff; border-radius:var(--card-radius); box-shadow:var(--shadow); padding:20px; }
    .summary-line{ display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; color:#444; }
    .summary-total{ display:flex; justify-content:space-between; align-items:center; font-weight:800; font-size:1.15rem; margin-top:10px; }
    .checkout-btn{ background:var(--accent); color:#fff; border:none; padding:10px 16px; border-radius:8px; font-weight:700; width:100%; }
    .checkout-btn:hover{ background:#166b35; }

    /* small */
    .muted{ color:#777; font-size:.9rem; }
    .empty-cart{ text-align:center; padding:40px 10px; color:#666; }

    @media (max-width: 991.98px){
      .cart-wrap{ flex-direction:column; }
      .cart-summary{ width:100%; position:relative; top:auto; }
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


<div class="container">
  
  <div class="cart-wrap">
    <div class="cart-list">
      <div class="cart-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Your shopping cart</h5>
          <div class="muted"><span id="itemsCount">0</span> items</div>
        </div>

        <?php
          // keep server fallback; client will override if localStorage present
          if (empty($cart_items)) {
            $cart_items = [];
          }
        ?>

        <div id="itemsContainer">
          <?php foreach($cart_items as $item): ?>
            <div class="cart-item" data-id="<?= htmlspecialchars($item['id']) ?>" data-price="<?= htmlspecialchars($item['price']) ?>">
              <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img">
              <div class="item-meta">
                <div class="d-flex justify-content-between align-items-start">
                  <div style="min-width:0">
                    <div class="item-title"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="item-desc muted">Freshly prepared • Ready in 10–20 mins</div>
                  </div>
                  <div class="text-end">
                    <div class="price">₱<?= number_format($item['price'],2) ?></div>
                    <?php if (!empty($item['old'])): ?><div class="old-price">₱<?= number_format($item['old'],2) ?></div><?php endif; ?>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div class="qty-controls">
                    <button class="btn btn-ghost btn-decrease" type="button" title="Decrease"><i class="bi bi-dash"></i></button>
                    <input type="number" class="form-control form-control-sm qty-input" min="1" value="<?= (int)$item['qty'] ?>" />
                    <button class="btn btn-ghost btn-increase" type="button" title="Increase"><i class="bi bi-plus"></i></button>
                    <button class="btn btn-outline-danger btn-sm ms-2 btn-remove">Remove</button>
                  </div>

                  <div class="text-end">
                    <div class="muted">Subtotal</div>
                    <div class="fw-bold item-subtotal">₱<?= number_format($item['price'] * $item['qty'],2) ?></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
          <a href="<?= site_url('PagesController/food_menu') ?>" class="btn btn-outline-secondary">← Continue shopping</a>
          <div>
            <button id="clearCart" class="btn btn-outline-danger">Clear cart</button>
          </div>
        </div>
      </div>
    </div>

    <aside class="cart-summary">
      <div class="summary-card">
        <h6 class="mb-3">Cart Summary</h6>

        <div class="summary-line">
          <div class="muted">Subtotal</div>
          <div id="subtotal">₱0.00</div>
        </div>

        <div class="summary-line">
          <div class="muted">Shipping</div>
          <div id="shipping">₱10.00</div>
        </div>

        <div class="summary-line">
          <div class="muted">Discount</div>
          <div id="discount">₱0.00</div>
        </div>

        <div class="summary-total">
          <div>Total</div>
          <div id="totalAmount">₱0.00</div>
        </div>

        <div class="mt-3">
          <div class="input-group mb-2">
            <input id="couponCode" type="text" class="form-control form-control-sm" placeholder="Promo code">
            <button id="applyCoupon" class="btn btn-outline-secondary btn-sm">Apply</button>
          </div>

          <a href="<?php echo site_url('PagesController/food_checkout'); ?>"><button class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button></a>
        </div>
      </div>
    </aside>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Cart logic (client-side demo) using localStorage
  (function(){
    const CART_KEY = 'foodsout_cart';
    const TAX = 0;
    const DEFAULT_SHIPPING = 10.00;

    function toNumber(v){ return Number(v) || 0; }
    function money(v){ return '₱' + Number(v).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}); }

    function getCart(){ try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch(e){ return []; } }
    function saveCart(cart){ localStorage.setItem(CART_KEY, JSON.stringify(cart)); updateCartBadge(); }

    function updateCartBadge(){
      const badge = document.getElementById('cartBadge');
      if (!badge) return;
      const cart = getCart();
      const totalQty = cart.reduce((s,i)=>s + (i.qty||0), 0);
      badge.textContent = totalQty;
    }

    function recalcItemSubtotal(itemEl){
      const price = toNumber(itemEl.dataset.price);
      const qty = toNumber(itemEl.querySelector('.qty-input').value);
      const subtotalEl = itemEl.querySelector('.item-subtotal');
      const subtotal = price * qty;
      subtotalEl.textContent = money(subtotal);
      return subtotal;
    }

    function recalcTotals(){
      const items = Array.from(document.querySelectorAll('.cart-item'));
      let subtotal = 0;
      items.forEach(it => subtotal += recalcItemSubtotal(it));
      document.getElementById('subtotal').textContent = money(subtotal);
      document.getElementById('shipping').textContent = money(DEFAULT_SHIPPING);
      let discount = toNumber(document.getElementById('discount').dataset.value) || 0;
      const total = subtotal + DEFAULT_SHIPPING - discount;
      document.getElementById('totalAmount').textContent = money(total);
      document.getElementById('itemsCount').textContent = items.reduce((s,el)=> s + (parseInt(el.querySelector('.qty-input').value)||0), 0);
    }

    function findParentItem(el){ return el.closest('.cart-item'); }

    // render DOM items from localStorage cart
    function renderCartFromStorage(){
      const cart = getCart();
      const container = document.getElementById('itemsContainer');
      if (!container) return;
      if (!cart || cart.length === 0) return; // keep server fallback if storage empty

      let html = '';
      cart.forEach(item => {
        const qty = item.qty || 1;
        const img = item.img || 'https://via.placeholder.com/96';
        const old = item.old ? ('<div class="old-price">₱' + parseFloat(item.old).toFixed(2) + '</div>') : '';
        html += `
          <div class="cart-item" data-id="${escapeHtml(item.id)}" data-price="${escapeHtml(item.price)}">
            <img src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}" class="product-img">
            <div class="item-meta">
              <div class="d-flex justify-content-between align-items-start">
                <div style="min-width:0">
                  <div class="item-title">${escapeHtml(item.name)}</div>
                  <div class="item-desc muted">${escapeHtml(item.desc || 'Freshly prepared • Ready in 10–20 mins')}</div>
                </div>
                <div class="text-end">
                  <div class="price">₱${parseFloat(item.price||0).toFixed(2)}</div>
                  ${old}
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="qty-controls">
                  <button class="btn btn-ghost btn-decrease" type="button" title="Decrease"><i class="bi bi-dash"></i></button>
                  <input type="number" class="form-control form-control-sm qty-input" min="1" value="${qty}" />
                  <button class="btn btn-ghost btn-increase" type="button" title="Increase"><i class="bi bi-plus"></i></button>
                  <button class="btn btn-outline-danger btn-sm ms-2 btn-remove">Remove</button>
                </div>

                <div class="text-end">
                  <div class="muted">Subtotal</div>
                  <div class="fw-bold item-subtotal">₱${(parseFloat(item.price||0)*qty).toFixed(2)}</div>
                </div>
              </div>
            </div>
          </div>
        `;
      });

      container.innerHTML = html;
    }

    // simple escaping for inserted HTML
    function escapeHtml(s){
      if (s === null || s === undefined) return '';
      return String(s).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]); });
    }

    document.addEventListener('DOMContentLoaded', function(){
      // render storage (if any) before wiring events so attachments bind to new elements
      renderCartFromStorage();
      updateCartBadge();

      // wire quantity controls
      document.querySelectorAll('.btn-increase').forEach(btn=>{
        btn.addEventListener('click', function(e){
          const item = findParentItem(this);
          const input = item.querySelector('.qty-input');
          input.value = Math.max(1, toNumber(input.value) + 1);
          // sync to storage
          syncDOMToStorage();
          recalcTotals();
        });
      });
      document.querySelectorAll('.btn-decrease').forEach(btn=>{
        btn.addEventListener('click', function(e){
          const item = findParentItem(this);
          const input = item.querySelector('.qty-input');
          input.value = Math.max(1, toNumber(input.value) - 1);
          syncDOMToStorage();
          recalcTotals();
        });
      });
      document.querySelectorAll('.qty-input').forEach(input=>{
        input.addEventListener('input', function(){
          if (this.value === '' || toNumber(this.value) < 1) this.value = 1;
          syncDOMToStorage();
          recalcTotals();
        });
      });

      // remove item
      document.querySelectorAll('.btn-remove').forEach(btn=>{
        btn.addEventListener('click', function(){
          const item = findParentItem(this);
          if (!item) return;
          if (!confirm('Remove this item from cart?')) return;
          const id = item.dataset.id;
          item.remove();
          removeFromStorageById(id);
          recalcTotals();
        });
      });

      // clear cart
      document.getElementById('clearCart').addEventListener('click', function(){
        if (!confirm('Clear all items from the cart?')) return;
        localStorage.removeItem(CART_KEY);
        document.getElementById('itemsContainer').innerHTML = '<div class="empty-cart">Your cart is empty.</div>';
        updateCartBadge();
        recalcTotals();
      });

      // coupon apply (existing)
      document.getElementById('applyCoupon').addEventListener('click', function(){
        const code = (document.getElementById('couponCode').value || '').trim().toUpperCase();
        const subtotalText = document.getElementById('subtotal').textContent.replace(/[₱,]/g,'') || '0';
        const subtotal = parseFloat(subtotalText) || 0;
        let discount = 0;
        if (code === 'SAVE10' && subtotal > 0) {
          discount = +(subtotal * 0.10).toFixed(2);
          document.getElementById('discount').textContent = money(discount);
          document.getElementById('discount').dataset.value = discount;
        } else if (code === '') {
          alert('Enter promo code.');
          return;
        } else {
          alert('Invalid promo code. Try SAVE10 (demo).');
          return;
        }
        recalcTotals();
      });

      // initial totals
      document.getElementById('discount').dataset.value = 0;
      recalcTotals();
    });

    // sync current DOM quantities back to localStorage
    function syncDOMToStorage(){
      const cart = [];
      document.querySelectorAll('.cart-item').forEach(el=>{
        const id = el.dataset.id;
        const price = parseFloat(el.dataset.price) || 0;
        const name = el.querySelector('.item-title') ? el.querySelector('.item-title').textContent.trim() : '';
        const imgEl = el.querySelector('img.product-img');
        const img = imgEl ? imgEl.src : '';
        const qty = parseInt(el.querySelector('.qty-input').value) || 1;
        cart.push({ id, name, price, img, qty });
      });
      saveCart(cart);
    }

    function removeFromStorageById(id){
      const cart = getCart().filter(i => String(i.id) !== String(id));
      saveCart(cart);
      updateCartBadge();
    }

    // save utility used above
    function saveCart(cart){ localStorage.setItem(CART_KEY, JSON.stringify(cart)); updateCartBadge(); }

  })();
</script>

</body>
</html>
