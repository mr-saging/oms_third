<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodsOUT Admin - Customers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { display: flex; min-height: 100vh; background: #f8f9fa; }
    .sidebar { width: 250px; background-color: #343a40; color: #fff; padding: 20px; }
    .sidebar h4 { text-align: center; margin-bottom: 20px; font-size: 1.2rem; }
    .sidebar a { display: block; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 8px; transition: background 0.2s; }
    .sidebar a:hover { background-color: #495057; }
    .content { flex-grow: 1; padding: 30px; }
    .card { border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<?php $this->load->view('admin_sidebar'); ?>

<div class="content">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Customers</h3>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm text-center" id="customersTable">
        <thead>
          <tr>
            <th>UserID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Birth Date</th>
            <th>Email Address</th>
            <th>Phone Number</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($customers)): ?>
            <?php foreach($customers as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c->userID) ?></td>
                <td><?= htmlspecialchars($c->first_name) ?></td>
                <td><?= htmlspecialchars($c->last_name) ?></td>
                <td><?= htmlspecialchars($c->username) ?></td>
                <td><?= htmlspecialchars($c->birth_date) ?></td>
                <td><?= htmlspecialchars($c->email_address) ?></td>
                <td><?= htmlspecialchars($c->phone_number) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">No customers available</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function() {
    $('#customersTable').DataTable();
  });
</script>
</body>
</html>
