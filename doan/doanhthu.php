<?php
include 'config/db.php';

// =========================
// 1️⃣ Lấy tháng và năm hiện tại hoặc từ bộ lọc
// =========================
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// =========================
// 2️⃣ Tính tháng trước
// =========================
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month == 0) {
  $prev_month = 12;
  $prev_year--;
}

// =========================
// 3️⃣ Dữ liệu tháng hiện tại
// =========================
$sql_total = "
    SELECT SUM(total_amount) AS total 
    FROM booking_history 
    WHERE MONTH(booking_date) = $month AND YEAR(booking_date) = $year
";
$total = $conn->query($sql_total)->fetch_assoc()['total'] ?? 0;

$sql_tickets = "
    SELECT COUNT(*) AS tickets 
    FROM booking_history 
    WHERE MONTH(booking_date) = $month AND YEAR(booking_date) = $year
";
$tickets = $conn->query($sql_tickets)->fetch_assoc()['tickets'] ?? 0;

// =========================
// 4️⃣ Dữ liệu tháng trước (để so sánh)
// =========================
$sql_prev_total = "
    SELECT SUM(total_amount) AS total 
    FROM booking_history 
    WHERE MONTH(booking_date) = $prev_month AND YEAR(booking_date) = $prev_year
";
$prev_total = $conn->query($sql_prev_total)->fetch_assoc()['total'] ?? 0;

$sql_prev_tickets = "
    SELECT COUNT(*) AS tickets 
    FROM booking_history 
    WHERE MONTH(booking_date) = $prev_month AND YEAR(booking_date) = $prev_year
";
$prev_tickets = $conn->query($sql_prev_tickets)->fetch_assoc()['tickets'] ?? 0;

// =========================
// 5️⃣ Tính phần trăm thay đổi
// =========================
function percent_change($current, $previous) {
  if ($previous == 0) return 100;
  return round((($current - $previous) / $previous) * 100, 1);
}

$revenue_change = percent_change($total, $prev_total);
$tickets_change = percent_change($tickets, $prev_tickets);

// =========================
// 6️⃣ Dữ liệu biểu đồ doanh thu theo ngày
// =========================
$sql_chart = "
    SELECT DATE(booking_date) AS day, SUM(total_amount) AS total
    FROM booking_history
    WHERE MONTH(booking_date) = $month AND YEAR(booking_date) = $year
    GROUP BY DATE(booking_date)
    ORDER BY day ASC
";
$chart_result = $conn->query($sql_chart);
$days = [];
$amounts = [];
while ($row = $chart_result->fetch_assoc()) {
    $days[] = date("d/m", strtotime($row['day']));
    $amounts[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Báo cáo doanh thu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-600">📊 Báo cáo doanh thu</h1>
        <span class="text-sm text-gray-500">Cập nhật: <?php echo date("d/m/Y"); ?></span>
      </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto px-6 py-8">
      
      <!-- Bộ lọc tháng/năm -->
      <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center bg-white p-4 rounded-2xl shadow">
        <div>
          <label class="text-gray-700 text-sm font-medium">Tháng</label>
          <select name="month" class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-400">
            <?php for ($m = 1; $m <= 12; $m++): ?>
              <option value="<?= $m ?>" <?= ($m == $month) ? 'selected' : '' ?>><?= $m ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div>
          <label class="text-gray-700 text-sm font-medium">Năm</label>
          <select name="year" class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-400">
            <?php $currentYear = date('Y');
            for ($y = $currentYear; $y >= $currentYear - 5; $y--): ?>
              <option value="<?= $y ?>" <?= ($y == $year) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">
          Xem thống kê
        </button>
      </form>

      <!-- Thống kê tổng quan -->
      <div class="grid md:grid-cols-3 gap-6 mb-10">
        <!-- Doanh thu -->
        <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-indigo-500">
          <h2 class="text-gray-500 text-sm mb-2 uppercase">Tổng doanh thu</h2>
          <p class="text-3xl font-semibold text-gray-900 mb-1">
            <?= number_format($total, 0, ',', '.'); ?> ₫
          </p>
          <div class="flex items-center text-sm">
            <?php if ($revenue_change >= 0): ?>
              <span class="text-emerald-600 font-medium">🔼 +<?= $revenue_change ?>%</span>
            <?php else: ?>
              <span class="text-red-500 font-medium">🔽 <?= $revenue_change ?>%</span>
            <?php endif; ?>
            <span class="ml-2 text-gray-500">so với tháng <?= $prev_month ?>/<?= $prev_year ?></span>
          </div>
        </div>

        <!-- Vé bán -->
        <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-emerald-500">
          <h2 class="text-gray-500 text-sm mb-2 uppercase">Tổng vé bán</h2>
          <p class="text-3xl font-semibold text-gray-900 mb-1"><?= $tickets; ?></p>
          <div class="flex items-center text-sm">
            <?php if ($tickets_change >= 0): ?>
              <span class="text-emerald-600 font-medium">🔼 +<?= $tickets_change ?>%</span>
            <?php else: ?>
              <span class="text-red-500 font-medium">🔽 <?= $tickets_change ?>%</span>
            <?php endif; ?>
            <span class="ml-2 text-gray-500">so với tháng <?= $prev_month ?>/<?= $prev_year ?></span>
          </div>
        </div>

        <!-- Ngày có doanh thu -->
        <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-pink-500">
          <h2 class="text-gray-500 text-sm mb-2 uppercase">Số ngày có doanh thu</h2>
          <p class="text-3xl font-semibold text-gray-900"><?= count($days); ?></p>
        </div>
      </div>

      <!-- Biểu đồ -->
      <div class="bg-white p-6 rounded-2xl shadow-md">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-indigo-600">📈 Doanh thu theo ngày</h2>
          <span class="text-sm text-gray-500">
            Tháng <?= $month ?>/<?= $year ?>
          </span>
        </div>
        <canvas id="revenueChart" height="90"></canvas>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-center py-4 text-sm text-gray-500">
      © <?= date("Y"); ?> Movie Admin Dashboard — Thiết kế với <span class="font-semibold text-indigo-600">Tailwind CSS</span>
    </footer>
  </div>

  <script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($days); ?>,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: <?= json_encode($amounts); ?>,
          borderColor: '#4F46E5',
          backgroundColor: 'rgba(79,70,229,0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 3,
          pointRadius: 5,
          pointBackgroundColor: '#4F46E5'
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false }},
        scales: {
          y: {
            beginAtZero: true,
            ticks: { callback: value => value.toLocaleString() + ' ₫' }
          },
          x: { ticks: { color: '#555' } }
        }
      }
    });
  </script>
</body>
</html>
