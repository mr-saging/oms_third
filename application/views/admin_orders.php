<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FoodsOUT Admin - Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <style>
    body { display:flex; min-height:100vh; background:#f8f9fa; }
    .content { flex-grow:1; padding:30px; }
    .card { border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
</head>
<body>

<?php $this->load->view('admin_sidebar'); ?>

<div class="content">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Orders</h3>
      <div>
        <!-- Add / Import removed per request -->
      </div>
    </div>

    <?php
      // flatten validation errors for alertify notifications
      $flat = [];
      if (!empty($errors) && is_array($errors)) {
          foreach ($errors as $k => $v) {
              if (is_array($v)) {
                  foreach ($v as $vv) $flat[] = trim(strip_tags($vv));
              } else {
                  $flat[] = trim(strip_tags($v));
              }
          }
      }
      $flat = array_values(array_filter(array_unique($flat)));
      $openEdit = !empty($open_edit_modal) ? $open_edit_modal : false;
    ?>

    <?php if(!empty($flat) || $openEdit): ?>
    <script>
      alertify.set('notifier','position', 'top-right');
      alertify.set('notifier','delay', 5);
      document.addEventListener('DOMContentLoaded', function() {
        var msgs = <?= json_encode($flat) ?> || [];
        msgs.forEach(function(m){
          if (m && m.length) alertify.error(m);
        });

        // if controller asked to open edit modal for a specific orderID
        <?php if($openEdit): ?>
          (function(){
            var id = <?= json_encode($openEdit) ?>;
            // try to find trigger button and open the shared modal after populating
            var btn = document.querySelector('.view-actions-btn[data-id="'+id+'"]');
            if (btn) {
              // ensure populate helper exists then populate + show
              var retry = 0;
              var waiter = setInterval(function(){
                if (typeof populateViewActions === 'function' || retry > 10) {
                  clearInterval(waiter);
                  if (typeof populateViewActions === 'function') {
                    populateViewActions(btn);
                    new bootstrap.Modal(document.getElementById('viewActionsModal')).show();
                  } else {
                    try { btn.click(); } catch(e) {}
                  }
                }
                retry++;
              }, 80);
            }
          })();
        <?php endif; ?>
      });
    </script>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <script>document.addEventListener('DOMContentLoaded', function(){ alertify.success(<?= json_encode(strip_tags($success)) ?>); });</script>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <script>document.addEventListener('DOMContentLoaded', function(){ alertify.error(<?= json_encode(strip_tags($error)) ?>); });</script>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm text-center">
        <thead class="table-light">
          <tr>
            <th>orderID</th>
            <th>userID</th>
            <th>storeID</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>View Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($orders)): ?>
            <?php foreach($orders as $o): ?>
              <tr>
                <td><?= $o->orderID ?></td>
                <td><?= $o->userID ?></td>
                <td><?= $o->storeID ?></td>
                <td><?= date('M d, Y H:i', strtotime($o->order_date)) ?></td>
                <td><?= htmlspecialchars($o->status) ?></td>
                <td>₱<?= number_format($o->total_amount, 2) ?></td>
                <td><?= htmlspecialchars($o->payment_method) ?></td>
                <td><?= htmlspecialchars($o->payment_status) ?></td>

                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary view-actions-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#viewActionsModal"
                    data-id="<?= (int)$o->orderID ?>"
                    data-userid="<?= (int)$o->userID ?>"
                    data-storeid="<?= (int)$o->storeID ?>"
                    data-order_date="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($o->order_date)), ENT_QUOTES) ?>"
                    data-status="<?= htmlspecialchars($o->status, ENT_QUOTES) ?>"
                    data-total="<?= htmlspecialchars($o->total_amount, ENT_QUOTES) ?>"
                    data-payment_method="<?= htmlspecialchars($o->payment_method, ENT_QUOTES) ?>"
                    data-payment_status="<?= htmlspecialchars($o->payment_status, ENT_QUOTES) ?>"
                    title="View actions"
                  ><i class="bi bi-eye"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="9" class="text-center text-muted">No orders available</td></tr>
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
        <h5 class="modal-title">Order Details & Actions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <strong>Order #</strong> <span id="vaOrderID"></span><br>
          <small class="text-muted">User ID: <span id="vaUserID"></span> • Store ID: <span id="vaStoreID"></span></small>
        </div>

        <hr>

        <form id="vaEditForm" action="<?= site_url('PagesController/update_order') ?>" method="post">
          <input type="hidden" name="orderID" id="va_orderID" value="">

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="va_status" class="form-select">
              <option value="Pending">Pending</option>
              <option value="In-Transit">In-Transit</option>
              <option value="Completed">Completed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Order Date</label>
            <input type="datetime-local" name="order_date" id="va_order_date" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Total Amount</label>
            <input type="text" name="total_amount" id="va_total_amount" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" id="va_payment_method" class="form-select" disabled>
              <option value="Cash">Cash</option>
              <option value="Credit Card">Credit Card</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Payment Status</label>
            <select name="payment_status" id="va_payment_status" class="form-select">
              <option value="Pending">Pending</option>
              <option value="Paid">Paid</option>
              <option value="Failed">Failed</option>
              <option value="Refunded">Refunded</option>
            </select>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- Removed Add / Import modals per request -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// populate & wire the single modal when opened
function populateViewActions(btn) {
  if (!btn) return;
  var id = btn.getAttribute('data-id') || '';
  var userid = btn.getAttribute('data-userid') || '';
  var storeid = btn.getAttribute('data-storeid') || '';
  var order_date = btn.getAttribute('data-order_date') || '';
  var status = btn.getAttribute('data-status') || '';
  var total_amount = btn.getAttribute('data-total') || '';
  var payment_method = btn.getAttribute('data-payment_method') || '';
  var payment_status = btn.getAttribute('data-payment_status') || '';

  document.getElementById('vaOrderID').textContent = id;
  document.getElementById('vaUserID').textContent = userid;
  document.getElementById('vaStoreID').textContent = storeid;

  document.getElementById('va_orderID').value = id;
  document.getElementById('va_status').value = status || 'Pending';
  document.getElementById('va_order_date').value = order_date;
  // normalize total: remove any non-digit except dot and minus, then format
  var norm = (total_amount + '').replace(/[^\d\.\-]/g, '');
  if (norm === '' || isNaN(Number(norm))) {
    document.getElementById('va_total_amount').value = '';
  } else {
    document.getElementById('va_total_amount').value = Number(norm).toFixed(2);
  }
  document.getElementById('va_payment_method').value = payment_method || '';
  document.getElementById('va_payment_status').value = payment_status || '';
}

// wire modal show + buttons after DOM ready
document.addEventListener('DOMContentLoaded', function(){
  var viewModalEl = document.getElementById('viewActionsModal');
  if (viewModalEl) {
    viewModalEl.addEventListener('show.bs.modal', function (event) {
      var btn = event.relatedTarget;
      if (btn) populateViewActions(btn);
    });
  }

  // ensure buttons also populate immediately on click
  document.querySelectorAll('.view-actions-btn').forEach(function(b){
    b.addEventListener('click', function(){ populateViewActions(this); });
  });
});
</script>
</body>
</html>
