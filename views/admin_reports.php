<script>
  const ctxR = document.getElementById('reportChart').getContext('2d');
  new Chart(ctxR, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($report_data,'store_name')) ?>,
      datasets: [{
        label: 'Total Sales (₱)',
        data: <?= json_encode(array_column($report_data,'total_sales')) ?>,
        backgroundColor: ['#f39c12', '#3498db', '#2ecc71', '#e74c3c']
      }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });
</script>
