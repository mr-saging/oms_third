<!-- admin_sidebar.php -->
<!DOCTYPE html>
<html>
<head>
    <title>FoodsOUT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center mb-4">FoodsOUT Admin</h4>
        <a href="<?= site_url('PagesController/dashboard_admin') ?>">🏠 Dashboard</a>
        <a href="<?= site_url('PagesController/orders_admin') ?>">📦 Orders</a>
        <a href="<?= site_url('PagesController/stores_admin') ?>">🏬 Stores</a>
        <a href="<?= site_url('PagesController/calendar_admin') ?>">🗓 Calendar</a>
        <a href="<?= site_url('PagesController/chat_admin') ?>">💬 Chat</a>
        <a href="<?= site_url('PagesController/products_admin') ?>">🍔 Menu / Products</a>
        <a href="<?= site_url('PagesController/customers_admin') ?>">👥 Customers</a>
        <a href="<?= site_url('PagesController/admin_logout') ?>" class="text-danger">🚪 Logout</a>
    </div>
