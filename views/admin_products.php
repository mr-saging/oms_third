<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodsOUT Admin - Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons (fixes missing icon glyphs) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AlertifyJS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

  <style>
    body { display: flex; min-height: 100vh; background: #f8f9fa; }
    .sidebar { width: 250px; background-color: #343a40; color: #fff; padding: 20px; }
    .sidebar h4 { text-align: center; margin-bottom: 20px; font-size: 1.2rem; }
    .sidebar a { display: block; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 8px; transition: background 0.2s; }
    .sidebar a:hover { background-color: #495057; }

    .content { flex-grow: 1; padding: 30px; }
    .card { border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .food-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    .action-btns .btn { margin-right:6px; }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
</head>
<body>

<?php $this->load->view('admin_sidebar'); ?>

<div class="content">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Product Management</h3>
      <div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button>
        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#importProductsModal">Import Products</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm text-center">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Store</th> <!-- NEW -->
            <th>Description</th>
            <th>Type</th>
            <th>Price</th>
            <th>Stocks</th>
            <th>Image</th>
            <th>View Actions</th> <!-- combined actions column -->
          </tr>
        </thead>
        <tbody>
        <?php
        // build a store map for quick lookup (safe if $stores passed from controller)
        $stores_map = [];
        if (!empty($stores) && is_array($stores)) {
            foreach($stores as $s) { $stores_map[$s->storeID] = $s->store_name; }
        }
        ?>
        <?php if(!empty($food_details)): ?>
          <?php foreach($food_details as $item): ?>
            <tr>
              <td><?= $item->foodID ?></td>
              <td><?= htmlspecialchars($item->food_name) ?></td>
              <td><?= htmlspecialchars(isset($stores_map[$item->storeID]) ? $stores_map[$item->storeID] : 'Unassigned') ?></td> <!-- NEW -->
              <td><?= htmlspecialchars($item->description ?? '') ?></td>
              <td><?= ucfirst($item->type) ?></td>
              <td>₱<?= number_format($item->price,2) ?></td>
              <td><?= $item->stocks ?></td>
              <td>
                <?php if(!empty($item->image) && file_exists('./uploads/'.$item->image)): ?>
                  <img src="<?= base_url('uploads/'.$item->image) ?>" class="food-img" alt="">
                <?php else: ?>
                  <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>

              <!-- Single actions column: eye (open modal with edit/delete inside) -->
              <td>
                <div class="action-btns">
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary view-actions-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#viewActionsModal"
                    data-id="<?= (int)$item->foodID ?>"
                    data-food_name="<?= htmlspecialchars($item->food_name, ENT_QUOTES) ?>"
                    data-storeid="<?= (int)($item->storeID ?? 0) ?>"            
                    data-store="<?= htmlspecialchars(isset($stores_map[$item->storeID]) ? $stores_map[$item->storeID] : '', ENT_QUOTES) ?>"
                    
                    data-description="<?= htmlspecialchars($item->description ?? '', ENT_QUOTES) ?>"
                    data-type="<?= htmlspecialchars($item->type, ENT_QUOTES) ?>"
                    data-price="<?= htmlspecialchars($item->price, ENT_QUOTES) ?>"
                    data-stocks="<?= htmlspecialchars($item->stocks, ENT_QUOTES) ?>"
                    data-image="<?= htmlspecialchars($item->image, ENT_QUOTES) ?>"
                    title="View actions"
                  ><i class="bi bi-eye"></i></button>
                </div>
              </td>
            </tr>

          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9" class="text-center text-muted">No products available</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Single reusable View Actions Modal (Edit form + Delete) -->
<div class="modal fade" id="viewActionsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="modal-title">Product Details & Actions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Info area -->
        <div class="d-flex gap-3 mb-3">
          <div style="width:96px;height:96px;flex:0 0 96px;">
            <img id="vaImage" src="" alt="preview" style="width:96px;height:96px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
          </div>
          <div class="flex-grow-1">
            <h6 id="vaName" class="mb-1"></h6>
            <div class="text-muted small mb-2" id="vaCode"></div>
            <div><span class="fw-bold text-danger" id="vaPrice"></span> <span class="text-muted" id="vaStocks"></span></div>
            <div class="text-muted small mt-1">Store: <span id="vaStoreName">—</span></div> <!-- NEW -->
          </div>
        </div>

        <hr>

        <!-- Edit form -->
        <form id="vaEditForm" action="<?= site_url('PagesController/update_food') ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="foodID" id="va_foodID" value="">
          <input type="hidden" name="old_image" id="va_old_image" value="">

          <div class="mb-3">
            <label class="form-label">Food Name</label>
            <input type="text" class="form-control" name="food_name" id="va_food_name" >
          </div>
          <div class="mb-3">
            <label class="form-label">Store</label>
            <select class="form-control" name="storeID" id="va_storeID" >
              <option value="">-- Select Store --</option>
              <?php if (!empty($stores)): foreach($stores as $s): ?>
                <option value="<?= (int)$s->storeID ?>"><?= htmlspecialchars($s->store_name) ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="va_description" rows="3" ><?= set_value('description') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <select class="form-control" name="type" id="va_type" >
              <option value="meal">Meal</option>
              <option value="drink">Drink</option>
              <option value="dessert">Dessert</option>
              <option value="snack">Snack</option>
              <option value="combo">Combo</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="text" step="0.01" class="form-control" name="price" id="va_price" >
          </div>
          <div class="mb-3">
            <label class="form-label">Stocks</label>
            <input type="number" class="form-control" name="stocks" id="va_stocks" min="0" >
          </div>
          <div class="mb-3">
            <label class="form-label">Replace Image</label>
            <input type="file" name="image" class="form-control">
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button type="button" id="vaDeleteBtn" class="btn btn-outline-danger">Delete</button>
            </div>
            <div>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">Add New Food</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?= site_url('PagesController/insert_food') ?>" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Food Name</label>
            <input type="text" class="form-control" name="food_name" value="<?= set_value('food_name') ?>" >
          </div>

          <div class="mb-3">
            <label class="form-label">Store</label>
            <select class="form-control" name="storeID" >
              <option value="">-- Select Store --</option>
              <?php if (!empty($stores)): foreach($stores as $s): ?>
                <option value="<?= (int)$s->storeID ?>" <?= set_select('storeID', $s->storeID) ?>><?= htmlspecialchars($s->store_name) ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3" ><?= set_value('description') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <select class="form-control" name="type" >
              <option value="">-- Select Type --</option>
              <option value="meal" <?= set_select('type', 'meal') ?>>Meal</option>
              <option value="drink" <?= set_select('type', 'drink') ?>>Drink</option>
              <option value="dessert" <?= set_select('type', 'dessert') ?>>Dessert</option>
              <option value="snack" <?= set_select('type', 'snack') ?>>Snack</option>
              <option value="combo" <?= set_select('type', 'combo') ?>>Combo</option>
              <option value="other" <?= set_select('type', 'other') ?>>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="text" step="0.01" class="form-control" name="price" value="<?= set_value('price') ?>"  min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Stocks</label>
            <input type="text" class="form-control" name="stocks" value="<?= set_value('stocks') ?>"  min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Import Products Modal -->
<div class="modal fade" id="importProductsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="importProductsForm" action="<?= site_url('PagesController/import_products') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header bg-light">
          <h5 class="modal-title">Import Products (Excel/CSV)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <input type="file" name="uploadFile" accept=".xls,.xlsx,.csv"  class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Inline AlertifyJS Error Handling & modal open logic -->
<?php if(!empty($errors) || !empty($open_add_modal) || !empty($open_edit_modal) || !empty($upload_error)): ?>
<script>
  alertify.set('notifier','position', 'top-right');
document.addEventListener('DOMContentLoaded', function() {
    <?php if(!empty($errors) && is_array($errors)): ?>
        <?php foreach($errors as $field => $msg): ?>
            <?php if(!empty($msg)): ?>
                alertify.error("<?= htmlspecialchars($msg) ?>");
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($upload_error)): ?>
        alertify.error("<?= htmlspecialchars($upload_error) ?>");
    <?php endif; ?>

    <?php if(!empty($open_add_modal)): ?>
        new bootstrap.Modal(document.getElementById('addProductModal')).show();
    <?php endif; ?>

    <?php if(!empty($open_edit_modal)): ?>
        // open the view actions modal for the item when server flagged open_edit_modal
        var openId = <?= json_encode($open_edit_modal) ?>;
        // find the trigger button for this ID
        var btn = document.querySelector('.view-actions-btn[data-id="'+openId+'"]');
        if (btn) {
          // use the same populate helper (defined later) — wait until it's available
          var retry = 0;
          var waiter = setInterval(function(){
            if (typeof populateViewActions === 'function' || retry > 10) {
              clearInterval(waiter);
              if (typeof populateViewActions === 'function') {
                populateViewActions(btn);
                new bootstrap.Modal(document.getElementById('viewActionsModal')).show();
              } else {
                // fallback: try click
                try { btn.click(); } catch(e) {}
              }
            }
            retry++;
          }, 80);
        }
    <?php endif; ?>
});
</script>

<?php endif; ?>

<!-- Flash messages shown via Alertify -->
<?php if (!empty($success)): ?>
  <script>document.addEventListener('DOMContentLoaded', function(){ alertify.success("<?= htmlspecialchars($success) ?>"); });</script>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <script>document.addEventListener('DOMContentLoaded', function(){ alertify.error("<?= htmlspecialchars($error) ?>"); });</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Helper to populate modal fields from a trigger button
function populateViewActions(btn) {
  if (!btn) return;
  var id = btn.getAttribute('data-id');
  var food_name = btn.getAttribute('data-food_name') || '';
  var description = btn.getAttribute('data-description') || '';
  var type = btn.getAttribute('data-type') || '';
  var price = btn.getAttribute('data-price') || '';
  var stocks = btn.getAttribute('data-stocks') || '';
  var image = btn.getAttribute('data-image') || '';
  var storeid = btn.getAttribute('data-storeid') || '';
  var storename = btn.getAttribute('data-store') || '';

  

  // info area
  var imgEl = document.getElementById('vaImage');
  imgEl.src = image ? '<?= base_url('uploads/') ?>'+image : 'https://via.placeholder.com/96?text=No+Image';
  document.getElementById('vaName').textContent = food_name;
  document.getElementById('vaCode').textContent = 'ID: ' + id; // fixed
  document.getElementById('vaPrice').textContent = '₱' + (parseFloat(price) ? parseFloat(price).toFixed(2) : '0.00');
  document.getElementById('vaStocks').textContent = stocks ? (' • Stocks: ' + stocks) : '';
  document.getElementById('vaStoreName').textContent = storename || 'Unassigned';

  // populate edit form
  document.getElementById('va_foodID').value = id;
  document.getElementById('va_old_image').value = image;
  document.getElementById('va_food_name').value = food_name;
  document.getElementById('va_description').value = description;
  document.getElementById('va_type').value = type || 'other';
  document.getElementById('va_price').value = price;
  document.getElementById('va_stocks').value = stocks;
  document.getElementById('va_storeID').value = storeid; // NEW: select value

  // wire delete button
  var delBtn = document.getElementById('vaDeleteBtn');
  delBtn.onclick = function(){
    if (!confirm('Delete this product?')) return;
    window.location.href = '<?= site_url("PagesController/delete_food") ?>/' + id;
  };
}

// Wire the modal show event so fields are populated from the trigger button
(function(){
  var modalEl = document.getElementById('viewActionsModal');
  if (modalEl) {
    modalEl.addEventListener('show.bs.modal', function (event) {
      var triggerBtn = event.relatedTarget; // the button that opened the modal
      if (triggerBtn && typeof populateViewActions === 'function') {
        populateViewActions(triggerBtn);
      }
    });
  }
})();

// AJAX import handler with notifier
(function(){
  var form = document.getElementById('importProductsForm');
  if (!form) return;

  form.addEventListener('submit', function(ev){
    ev.preventDefault();
    var submitBtn = form.querySelector('button[type="submit"]');
    var origTxt = submitBtn ? submitBtn.innerHTML : 'Importing...';
    if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = 'Importing…'; }

    var fd = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
    }).then(function(resp){
      // try JSON first
      return resp.text().then(function(text){
        try { return JSON.parse(text); } catch(e){ return { success: resp.ok, message: text || (resp.ok ? 'Import completed' : 'Import failed') }; }
      });
    }).then(function(data){
      if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = origTxt; }

      if (data && data.success) {
        alertify.set('notifier','position', 'top-right');
        alertify.success(data.message || 'Products imported successfully');
        // close modal
        var modalEl = document.getElementById('importProductsModal');
        var mod = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        mod.hide();
        // reload to show new products (short delay so modal hides smoothly)
        setTimeout(function(){ location.reload(); }, 800);
      } else {
        alertify.set('notifier','position', 'top-right');
        alertify.error(data && data.message ? data.message : 'Import failed. Check file and try again.');
      }
    }).catch(function(err){
      if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = origTxt; }
      alertify.set('notifier','position', 'top-right');
      alertify.error('Import error. See console for details.');
      console.error('Import error', err);
    });
  }, false);
})();
</script>
</body>
</html>
