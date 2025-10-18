<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container mt-4">
  <h3>Order List</h3>

  <!-- Filter + Search Form -->
  <form method="get" action="<?= site_url('orders/index') ?>" class="row mb-3">
    <div class="col-md-3">
      <input type="text" name="search" value="<?= $search ?>" class="form-control" placeholder="Search by customer">
    </div>
    <div class="col-md-3">
      <select name="store" class="form-control">
        <option value="">All Stores</option>
        <option value="Jollibee" <?= $store == 'Jollibee' ? 'selected' : '' ?>>Jollibee</option>
        <option value="McDo" <?= $store == 'McDo' ? 'selected' : '' ?>>McDo</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="status" class="form-control">
        <option value="">All Status</option>
        <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Delivered" <?= $status == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary">Filter</button>
      <a href="<?= site_url('orders') ?>" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- Table -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Store</th>
        <th>Status</th>
        <th>Total</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?= $o->id ?></td>
            <td><?= $o->customer_name ?></td>
            <td><?= $o->store ?></td>
            <td><?= $o->status ?></td>
            <td><?= $o->total ?></td>
            <td><?= $o->date_created ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">No records found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <div class="text-center">
    <?= $pagination ?>
  </div>
</div>

</body>
</html>