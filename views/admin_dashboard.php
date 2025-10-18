<?php $this->load->view('admin_sidebar'); ?>
<div class="content p-3">

    <?php
      // compute dynamic KPI counts (expects $orders, $stores, $customers to be provided by the controller)
      $pending = 0;
      $completed = 0;
      if (!empty($orders) && is_array($orders)) {
          foreach ($orders as $o) {
              $status = strtolower(trim($o->status ?? ''));
              if ($status === 'pending') $pending++;
              if ($status === 'completed') $completed++;
          }
      }

      $total_stores = !empty($stores) ? count($stores) : 0;
      // prefer explicit count passed from controller, fallback to counting $customers array
      $total_users  = isset($total_users) ? (int)$total_users : (!empty($customers) && is_array($customers) ? count($customers) : 0);
      // total orders = number of rows in orders array
      $total_orders = !empty($orders) && is_array($orders) ? count($orders) : 0;
      // total products = number of rows in products array (from food_details)
      $total_products = !empty($products) && is_array($products) ? count($products) : 0;

      // --- NEW: compute total sales from orders that are Completed AND Paid ---
      $total_sales = 0.0;
      if (!empty($orders) && is_array($orders)) {
          foreach ($orders as $o) {
              $status = strtolower(trim($o->status ?? ''));
              $payment = strtolower(trim($o->payment_status ?? $o->payment ?? ''));
              if ($status === 'completed' && $payment === 'paid') {
                  // try common amount field names
                  $amt = 0;
                  if (isset($o->total_amount)) $amt = $o->total_amount;
                  elseif (isset($o->total)) $amt = $o->total;
                  elseif (isset($o->amount)) $amt = $o->amount;
                  $total_sales += floatval($amt);
              }
          }
      }
    ?>

    <!-- KPI Cards (responsive: 2 per row on xs, 4 across on md+) -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm text-center h-100">
                <small class="text-muted">Orders</small>
                <h4 class="mb-0"><?= (int)$total_orders ?></h4>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm text-center h-100">
                <small class="text-muted">Total Products</small>
                <h4 class="mb-0"><?= (int)$total_products ?></h4>
            </div>
        </div>


        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm text-center h-100">
                <small class="text-muted">Total Stores</small>
                <h4 class="mb-0"><?= (int)$total_stores ?></h4>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm text-center h-100">
                <small class="text-muted">Total Customers</small>
                <h4 class="mb-0"><?= (int)$total_users ?></h4>
            </div>
        </div>
    </div>

    <!-- Chart Controls (global  switch) -->
    <div class="d-flex gap-3 align-items-center mb-2">
        <label class="mb-0"> Range:</label>
        <select id="dataRange" class="form-select w-auto">
            <option value="monthly" selected>Monthly</option>
            <option value="weekly">Weekly</option>
            <option value="daily">Daily</option>
        </select>
    </div>

    <!-- Charts -->
    <div class="row mt-3 g-3">
        <div class="col-lg-6 col-12">
            <div class="card p-3 shadow-sm chart-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">Sales</h6>
                        <!-- moved total sales into header as a small card-like div -->
                        <div class="sales-header-total mt-1">
                            <small class="text-muted d-block">Total Sales</small>
                            <div class="fs-6 fw-bold"><?= number_format($total_sales, 2) ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="salesType">
                            <option value="bar" selected>Bar</option>
                            <option value="line">Line</option>
                            <option value="pie">Pie</option>
                            <option value="radar">Radar</option>
                        </select>
                        <div class="btn-group btn-group-sm" role="group" aria-label="quick-sales-type">
                            <button class="btn btn-outline-secondary" data-target="#sales" data-type="bar">Bar</button>
                            <button class="btn btn-outline-secondary" data-target="#sales" data-type="line">Line</button>
                            <button class="btn btn-outline-secondary" data-target="#sales" data-type="pie">Pie</button>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="card p-3 shadow-sm chart-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">Users</h6>
                        <small class="text-muted">New Users </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="usersType">
                            <option value="line" selected>Line</option>
                            <option value="bar">Bar</option>
                            <option value="doughnut">Doughnut</option>
                        </select>
                        <div class="btn-group btn-group-sm" role="group" aria-label="quick-users-type">
                            <button class="btn btn-outline-secondary" data-target="#users" data-type="line">Line</button>
                            <button class="btn btn-outline-secondary" data-target="#users" data-type="bar">Bar</button>
                            <button class="btn btn-outline-secondary" data-target="#users" data-type="doughnut">Donut</button>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* static  datasets (monthly / weekly / daily) */
const SAMPLE = {
    monthly: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        users: [50,70,60,90,110,120,130,150,140,115,95,100]
    },
    weekly: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        sales: [2000,2500,1800,3000,3200,4200,3900],
        users: [8,12,9,15,18,22,20]
    },
    daily: {
        labels: ['00:00','04:00','08:00','12:00','16:00','20:00'],
        sales: [400,800,1600,2400,1800,1200],
        users: [1,3,8,18,12,6]
    }
};

/* --- DYNAMIC SALES DATA (from server) --- */
/* Provide aggregated sales datasets per range (monthly/weekly/daily) */
const DYN_SALES = {
    monthly: {
        labels: <?= json_encode(isset($sales_month_labels) ? $sales_month_labels : []) ?>,
        sales:  <?= json_encode(isset($sales_month_values) ? $sales_month_values : []) ?>
    },
    weekly: {
        labels: <?= json_encode(isset($sales_week_labels) ? $sales_week_labels : []) ?>,
        sales:  <?= json_encode(isset($sales_week_values) ? $sales_week_values : []) ?>
    },
    daily: {
        labels: <?= json_encode(isset($sales_day_labels) ? $sales_day_labels : []) ?>,
        sales:  <?= json_encode(isset($sales_day_values) ? $sales_day_values : []) ?>
    }
};
/* ---------------------------------------- */

/* --- DYNAMIC USERS DATA (from server) ---
   Provide three ranges so client can switch without extra requests */
const DYN_USERS = {
    monthly: {
        labels: <?= json_encode(isset($users_month_labels) ? $users_month_labels : []) ?>,
        users:  <?= json_encode(isset($users_month_values) ? $users_month_values : []) ?>
    },
    weekly: {
        labels: <?= json_encode(isset($users_week_labels) ? $users_week_labels : []) ?>,
        users:  <?= json_encode(isset($users_week_values) ? $users_week_values : []) ?>
    },
    daily: {
        labels: <?= json_encode(isset($users_day_labels) ? $users_day_labels : []) ?>,
        users:  <?= json_encode(isset($users_day_values) ? $users_day_values : []) ?>
    }
};
/* ---------------------------------------- */

/* chart options */
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'top' }, tooltip: { mode:'index', intersect:false } },
    scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
};

let charts = {};

/* create a chart (destroy if exists) */
function createChart(key, ctx, type, data, optionsOverride = {}) {
    if (charts[key]) charts[key].destroy();
    const cfg = {
        type: type,
        data: data,
        options: Object.assign({}, commonOptions, optionsOverride)
    };
    charts[key] = new Chart(ctx, cfg);
}

/* build data object for Chart.js from sample */
function buildData(labels, datasetLabel, values, backgroundColor) {
    return {
        labels: labels,
        datasets: [{
            label: datasetLabel,
            data: values,
            backgroundColor: backgroundColor,
            borderColor: backgroundColor,
            fill: (datasetLabel === 'New Users'),
            tension: 0.3
        }]
    };
}

/* initial render */
function renderAll(range = 'monthly') {
    const sample = SAMPLE[range];

    // Sales: prefer dynamic aggregated dataset for selected range, otherwise fall back to sample
    const salesRange = range;
    const salesLabels = (DYN_SALES[salesRange] && Array.isArray(DYN_SALES[salesRange].labels) && DYN_SALES[salesRange].labels.length)
        ? DYN_SALES[salesRange].labels
        : sample.labels;
    const salesValues = (DYN_SALES[salesRange] && Array.isArray(DYN_SALES[salesRange].sales) && DYN_SALES[salesRange].sales.length)
        ? DYN_SALES[salesRange].sales
        : (sample.sales || []);
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = buildData(salesLabels, 'Revenue', salesValues, 'rgba(13,110,253,0.8)');
    const salesType = document.getElementById('salesType').value || 'bar';
    const salesOpts = (salesType === 'pie' || salesType === 'radar') ? { plugins: { legend:{position:'right'} } } : {};
    createChart('sales', salesCtx, salesType, salesData, salesOpts);

    // Users: prefer dynamic dataset for selected range, otherwise fall back to sample
    const usersRange = range;
    const usersLabels = (DYN_USERS[usersRange] && Array.isArray(DYN_USERS[usersRange].labels) && DYN_USERS[usersRange].labels.length)
        ? DYN_USERS[usersRange].labels
        : sample.labels;
    const usersValues = (DYN_USERS[usersRange] && Array.isArray(DYN_USERS[usersRange].users) && DYN_USERS[usersRange].users.length)
        ? DYN_USERS[usersRange].users
        : sample.users;
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersData = buildData(usersLabels, 'New Users', usersValues, 'rgba(25,135,84,0.8)');
    const usersType = document.getElementById('usersType').value || 'line';
    const usersOpts = (usersType === 'doughnut') ? { plugins: { legend:{position:'right'} }, scales: {} } : {};
    createChart('users', usersCtx, usersType, usersData, usersOpts);
}

/* UI interactions: change chart type via selects or quick buttons */
document.addEventListener('DOMContentLoaded', function(){
    // initial
    renderAll(document.getElementById('dataRange').value);

    // data range change
    document.getElementById('dataRange').addEventListener('change', function(){
        renderAll(this.value);
    });

    // per-chart selects
    document.getElementById('salesType').addEventListener('change', function(){
        renderAll(document.getElementById('dataRange').value);
    });
    document.getElementById('usersType').addEventListener('change', function(){
        renderAll(document.getElementById('dataRange').value);
    });

    // quick-type buttons (data-type attribute)
    document.querySelectorAll('.btn-group button').forEach(btn => {
        btn.addEventListener('click', function(){
            const tgt = this.getAttribute('data-target');
            const type = this.getAttribute('data-type');
            if (tgt === '#sales') {
                document.getElementById('salesType').value = type;
            } else if (tgt === '#users') {
                document.getElementById('usersType').value = type;
            }
            renderAll(document.getElementById('dataRange').value);
        });
    });

    // responsive redraw on container resize
    const ro = new ResizeObserver(() => {
        Object.values(charts).forEach(c => c && c.resize());
    });
    document.querySelectorAll('.chart-wrapper').forEach(el => ro.observe(el));
});
</script>

<style>
/* override to make dashboard full-bleed */
body { min-height:100vh; }

/* make the content area fill available space and remove centered max-width */
.content {
    max-width: none !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 12px !important; /* small padding all around */
    box-sizing: border-box;
}

/* remove the bootstrap utility padding set via class="p-3" if present */
.content.p-3 { padding: 12px !important; } /* ensure small padding even when p-3 exists */

/* keep other styles */
.card { border-radius: 10px; }

/* chart card layout: chart-wrapper fills available area so canvas scales */
.chart-card { display:flex; flex-direction:column; height:100%; min-height:260px; }
.chart-wrapper { flex:1 1 auto; position:relative; width:100%; }

/* ensure canvas always fills wrapper */
.chart-wrapper canvas { width:100% !important; height:100% !important; display:block; }

/* overlay styling for total sales inside chart */
.sales-header-total {
    display: inline-block;
    background: #fff;
    padding: 6px 10px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    text-align: left;
    line-height: 1;
}
.sales-header-total small { font-size: 0.75rem; }
.sales-header-total .fs-6 { font-size: 0.95rem; }
/* small responsive tweaks */
@media (max-width: 767.98px) {
     .chart-card { min-height:320px; }
}
</style>
