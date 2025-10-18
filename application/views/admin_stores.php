<?php $this->load->view("admin_sidebar"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FoodsOUT Admin - Stores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons (needed for <i class="bi ...">) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AlertifyJS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

  <style>
    body { display:flex; min-height:100vh; background:#f8f9fa; }
    .content { flex-grow:1; padding:30px; }
    .card { border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
</head>
<body>

<div class="content">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Store Management</h3>
      <div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStoreModal">Add New Store</button>
        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#importStoresModal">Import Stores</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm">
        <thead class="table-light">
          <tr>
            <th>Store ID</th>
            <th>Store Name</th>
            <th>Address</th>
            <th>Created At</th>
            <th>View Actions</th> <!-- combined column -->
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($stores)): ?>
            <?php foreach ($stores as $s): ?>
              <tr>
                <td><?= $s->storeID ?></td>
                <td><?= htmlspecialchars($s->store_name) ?></td>
                <td><?= htmlspecialchars($s->address) ?></td>
                <td><?= date("M d, Y H:i", strtotime($s->created_at)) ?></td>
                <td class="text-center">
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary view-store-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#viewStoreModal"
                    data-id="<?= (int)$s->storeID ?>"
                    data-name="<?= htmlspecialchars($s->store_name, ENT_QUOTES) ?>"
                    data-address="<?= htmlspecialchars($s->address, ENT_QUOTES) ?>"
                    data-created="<?= date("Y-m-d H:i:s", strtotime($s->created_at)) ?>"
                    title="View actions"
                  ><i class="bi bi-eye"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No stores available</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Store Modal -->
<div class="modal fade" id="addStoreModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">Add New Store</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">


        <form action="<?= site_url(
            "PagesController/insert_store"
        ) ?>" method="post">

        
          <div class="mb-3">
            <label class="form-label">Store Name</label>
            <input type="text" name="store_name" class="form-control" value="<?= set_value(
                "store_name"
            ) ?>" >
          </div>


          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" ><?= set_value(
                "address"
            ) ?></textarea>
          </div>


          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Store</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Import Stores Modal -->
<div class="modal fade" id="importStoresModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Form -->
      <form action="<?= site_url(
          "PagesController/import_stores"
      ) ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header bg-light">
          <h5 class="modal-title">Import Stores (Excel / CSV)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>


        <div class="modal-body">
          <div class="mb-3">
            <input type="file" name="uploadFile" accept=".xls,.xlsx,.csv" class="form-control">
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

<!-- View Actions Modal (Edit + Delete combined) -->
<div class="modal fade" id="viewStoreModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="viewStoreForm" method="post" action="<?= site_url('PagesController/update_store') ?>">
        <div class="modal-header bg-light">
          <h5 class="modal-title">Store Details & Actions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="vs_id" value="">

          <div class="mb-3">
            <label class="form-label">Store Name</label>
            <input type="text" name="store_name" id="vs_name" class="form-control" >
          </div>

          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" id="vs_address" class="form-control" rows="3" ></textarea>
          </div>

          <div class="mb-2 text-muted small">
            Created at: <span id="vs_created">-</span>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-between">
          <div>
            <button type="button" id="vsDeleteBtn" class="btn btn-outline-danger">Delete</button>
          </div>
          <div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Store</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id) {
  if (confirm('Are you sure you want to delete this store?')) {
    window.location.href = '<?= site_url(
        "PagesController/delete_store"
    ) ?>/' + id;
  }
}

document.addEventListener('DOMContentLoaded', function(){

  // Delegate clicks on view buttons — populate modal fields and open modal
  document.body.addEventListener('click', function(e){
    var btn = e.target.closest('.view-store-btn');
    if (!btn) return;

    e.preventDefault();

    var id = btn.getAttribute('data-id') || '';
    var name = btn.getAttribute('data-name') || '';
    var address = btn.getAttribute('data-address') || '';
    var created = btn.getAttribute('data-created') || '';

    // populate modal inputs/display
    var vsId = document.getElementById('vs_id');
    var vsName = document.getElementById('vs_name');
    var vsAddress = document.getElementById('vs_address');
    var vsCreated = document.getElementById('vs_created');

    if (vsId) vsId.value = id;
    if (vsName) vsName.value = name;
    if (vsAddress) vsAddress.value = address;
    if (vsCreated) vsCreated.textContent = created ? created : '-';

    // wire delete button for this id
    var delBtn = document.getElementById('vsDeleteBtn');
    if (delBtn) {
      delBtn.onclick = function(){
        if (!confirm('Delete this store?')) return;
        window.location.href = '<?= site_url("PagesController/delete_store") ?>/' + id;
      };
    }

    // show the modal (Bootstrap 5)
    var modalEl = document.getElementById('viewStoreModal');
    if (modalEl) {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }
  });

});
</script>

<!-- Alertify notifier handling & validation messages -->
<?php if (
    !empty($errors) ||
    !empty($open_add_modal) ||
    !empty($open_edit_modal) ||
    $this->session->flashdata("message") ||
    $this->session->flashdata("success") ||
    $this->session->flashdata("error")
): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  alertify.set('notifier','position', 'top-right');

  <?php if (!empty($errors) && is_array($errors)): ?>
    <?php foreach ($errors as $field => $msg): ?>
      <?php if (!empty($msg)): ?>
        // strip tags to avoid showing raw HTML like <p>...</p>
        alertify.error("<?= addslashes(htmlspecialchars(strip_tags($msg))) ?>");
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($open_add_modal)): ?>
    new bootstrap.Modal(document.getElementById('addStoreModal')).show();
  <?php endif; ?>

  <?php if (!empty($open_edit_modal)): ?>
    // open edit modal for provided id
    new bootstrap.Modal(document.getElementById('editStoreModal<?= $open_edit_modal ?>')).show();
  <?php endif; ?>

  <?php if ($this->session->flashdata("success")): ?>
    alertify.success("<?= addslashes(
        htmlspecialchars(strip_tags($this->session->flashdata("success")))
    ) ?>");
  <?php endif; ?>

  <?php if ($this->session->flashdata("error")): ?>
    alertify.error("<?= addslashes(
        htmlspecialchars(strip_tags($this->session->flashdata("error")))
    ) ?>");
  <?php endif; ?>

  <?php if (
      $this->session->flashdata("message") &&
      !(
          $this->session->flashdata("success") ||
          $this->session->flashdata("error")
      )
  ): ?>
    // legacy single flash 'message' - show as success by default (tags stripped)
    alertify.success("<?= addslashes(
        htmlspecialchars(strip_tags($this->session->flashdata("message")))
    ) ?>");
  <?php endif; ?>
});
</script>
<?php endif; ?>

</body>
</html>
