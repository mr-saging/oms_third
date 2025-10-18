<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{ --accent:#1f8a49; --card-radius:14px; --shadow:0 8px 20px rgba(18,18,18,0.06); --hover-orange:#ff7a00; }
    body{ background:linear-gradient(180deg,#fbf9f6 0,#fff 100%); font-family:Inter, Arial, sans-serif; color:#222; padding-top:72px; }
    .product-card{ border-radius:var(--card-radius); box-shadow:var(--shadow); background:#fff; overflow:hidden; display:flex; gap:1rem; padding:1rem; align-items:center; border-left:4px solid transparent; }
    .product-card:hover{ transform:translateY(-6px); border-left-color:var(--hover-orange); }
    .product-image{ width:120px; height:120px; flex:0 0 120px; border-radius:12px; overflow:hidden; }
    .product-image img{ width:100%; height:100%; object-fit:cover; display:block; }
    .price{ font-weight:700; color:var(--accent); font-size:1.05rem; }
    .muted-small{ font-size:.9rem; color:#6b6b6b; }

    /* Categories - vertical side list */
    .category-list { display:flex; flex-direction:column; gap:.5rem; padding:0; margin:0; }
    .category-chip {
      display:block;
      padding:.6rem .75rem;
      border-radius:8px;
      background:#fff;
      border:1px solid #eee;
      color:#333;
      cursor:pointer;
      font-weight:600;
      transition: all .12s ease;
      text-align:left;
    }
    .category-chip:hover{ background:var(--hover-orange); color:#fff; border-color:var(--hover-orange); transform:translateX(4px); }
    .category-chip.active{ background:var(--hover-orange); color:#fff; border-color:var(--hover-orange); box-shadow:0 6px 14px rgba(0,0,0,0.06); }

    /* price filter */
    .price-filter { margin-top: .6rem; display:flex; gap:.5rem; align-items:center; }
    .price-filter input[type="number"]{ width:110px; }
    .price-filter .btn-apply{ background:var(--hover-orange); color:#fff; border:none; }
    @media (max-width:767.98px){ .product-card{ padding:.75rem; } .product-image{ width:88px; height:88px; } .category-list{ flex-direction:row; flex-wrap:wrap; } .category-chip{ border-radius:999px; } }
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

  <div class="container-fluid py-4">
    <div class="row gx-4">
      <aside class="col-lg-2">
        <!-- categories & price filter (vertical) -->
        <div class="mb-3">
          <h6 class="fw-bold">CATEGORIES</h6>
          <div class="category-list" id="categoryList" role="list">
            <div class="category-chip active" data-cat="all" role="listitem">All</div>
            <div class="category-chip" data-cat="meal" role="listitem">Meal</div>
            <div class="category-chip" data-cat="drink" role="listitem">Drink</div>
            <div class="category-chip" data-cat="dessert" role="listitem">Dessert</div>
            <div class="category-chip" data-cat="snack" role="listitem">Snack</div>
            <div class="category-chip" data-cat="combo" role="listitem">Combo</div>
            <div class="category-chip" data-cat="other" role="listitem">Other</div>
          </div>
        </div>

        <div class="mb-3">
          <h6 class="fw-bold">PRICE RANGE</h6>
          <div class="price-filter">
            <input type="number" id="priceMin" class="form-control form-control-sm" placeholder="Min" min="0" step="1">
            <input type="number" id="priceMax" class="form-control form-control-sm" placeholder="Max" min="0" step="1">
            <button id="applyPriceFilter" class="btn btn-apply btn-sm">Apply</button>
            <button id="resetFilters" class="btn btn-outline-secondary btn-sm">Reset</button>
          </div>
        </div>
      </aside>

      <main class="col-lg-9">
        <div class="row g-3" id="productsWrapper">
          <?php
            if (empty($products)) {
                $products = (isset($products) && is_array($products)) ? $products : [];
            }

            if (!empty($products)):
              foreach ($products as $p):
                $id = isset($p->foodID) ? $p->foodID : (isset($p['foodID']) ? $p['foodID'] : '');
                $name = isset($p->food_name) ? $p->food_name : ($p['food_name'] ?? 'Unnamed');
                $desc = isset($p->description) ? $p->description : ($p['description'] ?? '');
                $type = isset($p->type) ? strtolower($p->type) : (isset($p['type']) ? strtolower($p['type']) : 'other');
                $price = isset($p->price) ? (float)$p->price : (isset($p['price']) ? (float)$p['price'] : 0);
                $stocks = isset($p->stocks) ? $p->stocks : ($p['stocks'] ?? 0);
                $img = isset($p->image) && !empty($p->image) && file_exists('./uploads/'.$p->image) ? base_url('uploads/'.$p->image) : (isset($p->image) && filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : base_url('assets/img/placeholder.png'));
          ?>
            <div class="col-md-6 col-lg-4 product-col" data-type="<?= htmlspecialchars($type) ?>" data-price="<?= htmlspecialchars($price) ?>">
              <div class="product-card">
                <div class="product-image">
                  <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="product-meta" style="min-width:0;">
                  <div class="d-flex justify-content-between align-items-start mb-1">
                    <h6 class="mb-1 fw-bold" style="margin:0"><?= htmlspecialchars($name) ?></h6>
                    <div class="text-end">
                      <div class="price">₱<?= number_format((float)$price,2) ?></div>
                      <div class="muted-small">Stocks: <?= (int)$stocks ?></div>
                    </div>
                  </div>

                  <p class="muted-small mb-2" style="min-height:42px;"><?= htmlspecialchars($desc) ?></p>

                  <div class="d-flex justify-content-between align-items-center">
                    <div class="muted-small"><?= htmlspecialchars(ucfirst($type)) ?></div>
                    <div>
                      <button class="btn btn-success btn-sm add-to-cart"
                        type="button"
                        data-id="<?= htmlspecialchars($id) ?>"
                        data-name="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                        data-price="<?= htmlspecialchars($price) ?>"
                        data-img="<?= htmlspecialchars($img, ENT_QUOTES) ?>"
                        data-desc="<?= htmlspecialchars($desc, ENT_QUOTES) ?>"
                        data-stocks="<?= htmlspecialchars($stocks) ?>"
                      ><i class="bi bi-cart3 me-1"></i>Order</button>

                      <button class="btn btn-outline-secondary btn-sm ms-1 quick-view" type="button"
                        data-name="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                        data-img="<?= htmlspecialchars($img, ENT_QUOTES) ?>"
                        data-desc="<?= htmlspecialchars($desc, ENT_QUOTES) ?>"
                        data-price="<?= htmlspecialchars($price) ?>"
                      ><i class="bi bi-eye"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php
              endforeach;
            else:
          ?>
            <div class="col-12 text-center text-muted py-5">No products available.</div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Quick View Modal (keeps behavior from previous file) -->
  <div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content p-3">
        <div class="row g-3">
          <div class="col-md-5"><img id="qvImg" src="" alt="" class="img-fluid rounded"></div>
          <div class="col-md-7">
            <h4 id="qvName"></h4>
            <div class="mb-2"><span id="qvPrice" class="price"></span></div>
            <p id="qvDesc" class="text-muted small"></p>
            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-success" id="qvAddBtn">Add to cart</button>
              <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  /* LocalStorage cart helpers */
  const CART_KEY = 'foodsout_cart';

  function getCart(){
    try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch(e){ return []; }
  }
  function saveCart(cart){ localStorage.setItem(CART_KEY, JSON.stringify(cart)); updateCartBadge(); }

  function updateCartBadge(){
    const badge = document.getElementById('cartBadge');
    if (!badge) return;
    const cart = getCart();
    const totalQty = cart.reduce((s,i)=>s + (i.qty||0), 0);
    badge.textContent = totalQty;
  }

  /* add item: increments qty if exists, else push */
  function addToCartLocal(item){
    if (!item || !item.id) return;
    const cart = getCart();
    const idx = cart.findIndex(i => String(i.id) === String(item.id));
    if (idx > -1){
      cart[idx].qty = (cart[idx].qty||0) + (item.qty || 1);
    } else {
      cart.push(Object.assign({qty: 1}, item));
    }
    saveCart(cart);
    // small UI feedback
    alert(item.name + ' added to cart — ₱' + (parseFloat(item.price)||0).toFixed(2));
  }

  /* Quick view wiring + order button wiring */
  document.addEventListener('DOMContentLoaded', function(){
    updateCartBadge();

    // filtering state
    let activeCategory = 'all';
    const priceMinInput = document.getElementById('priceMin');
    const priceMaxInput = document.getElementById('priceMax');
    const categoryChips = document.querySelectorAll('.category-chip');
    const productCols = document.querySelectorAll('.product-col');

    function applyFilters(){
      const min = parseFloat(priceMinInput.value) || 0;
      const max = parseFloat(priceMaxInput.value) || Infinity;

      productCols.forEach(col=>{
        const pType = (col.dataset.type || '').toLowerCase();
        const pPrice = parseFloat(col.dataset.price) || 0;
        let visible = true;
        if (activeCategory && activeCategory !== 'all' && pType !== activeCategory) visible = false;
        if (pPrice < min || pPrice > max) visible = false;
        col.style.display = visible ? '' : 'none';
      });
    }

    // category chips click
    categoryChips.forEach(chip=>{
      chip.addEventListener('click', function(){
        categoryChips.forEach(c=>c.classList.remove('active'));
        this.classList.add('active');
        activeCategory = this.dataset.cat || 'all';
        applyFilters();
      });
    });

    // price apply / reset
    document.getElementById('applyPriceFilter').addEventListener('click', function(e){
      e.preventDefault();
      applyFilters();
    });
    document.getElementById('resetFilters').addEventListener('click', function(e){
      e.preventDefault();
      priceMinInput.value = '';
      priceMaxInput.value = '';
      document.querySelector('.category-chip.active').classList.remove('active');
      document.querySelector('.category-chip[data-cat="all"]').classList.add('active');
      activeCategory = 'all';
      applyFilters();
    });

    // existing quick view + add-to-cart wiring
    document.querySelectorAll('.quick-view').forEach(btn=>{
      btn.addEventListener('click', function(){
        const name = this.dataset.name || '';
        const img = this.dataset.img || '';
        const desc = this.dataset.desc || '';
        const price = this.dataset.price || 0;
        document.getElementById('qvImg').src = img;
        document.getElementById('qvName').textContent = name;
        document.getElementById('qvPrice').textContent = '₱' + (parseFloat(price)||0).toFixed(2);
        document.getElementById('qvDesc').textContent = desc;
        const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
        modal.show();
        document.getElementById('qvAddBtn').onclick = function(){
          addToCartLocal({
            id: btn.closest('.product-card').querySelector('.add-to-cart').dataset.id,
            name, price, img, desc
          });
          modal.hide();
        };
      });
    });

    document.querySelectorAll('.add-to-cart').forEach(btn=>{
      btn.addEventListener('click', function(e){
        e.stopPropagation();
        addToCartLocal({
          id: this.dataset.id,
          name: this.dataset.name,
          price: this.dataset.price,
          img: this.dataset.img,
          desc: this.dataset.desc,
          stocks: this.dataset.stocks
        });
      });
    });
  });
  </script>
</body>
</html>
