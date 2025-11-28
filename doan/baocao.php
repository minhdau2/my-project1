<?php
session_start();
include_once("config/db.php");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Admin Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Font chữ tùy chỉnh */
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        /* Ẩn hiện section */
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    <aside class="w-64 bg-slate-900 text-white fixed h-full shadow-xl z-10 transition-transform">
        <div class="p-6 text-center border-b border-slate-700">
            <h4 class="text-2xl font-bold tracking-wider text-blue-400">ADMIN</h4>
        </div>
        <nav class="mt-6">
            <a href="#" onclick="showSection('dashboard', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 bg-blue-600 border-l-4 border-white">
                <i class="fa-solid fa-house mr-3"></i> Dashboard
            </a>
            <a href="#" onclick="showSection('movies', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-film mr-3"></i> Quản lý Phim
            </a>
            <a href="#" onclick="showSection('tickets', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-ticket mr-3"></i> Quản lý Vé
            </a>
            <a href="#" onclick="showSection('seats', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-couch mr-3"></i> Quản lý Ghế
            </a>
            <a href="#" onclick="showSection('shows', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-calendar-days mr-3"></i> Quản lý Suất Chiếu
            </a>
            <a href="#" onclick="showSection('users', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-users mr-3"></i> Quản lý Người Dùng
            </a>
            <a href="#" onclick="showSection('staff', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-user-tie mr-3"></i> Quản lý Nhân Viên
            </a>
            <a href="#" onclick="showSection('genres', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-tags mr-3"></i> Quản lý Thể Loại
            </a>

            <a href="#" onclick="showSection('reports', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-chart-pie mr-3"></i> Báo Cáo
            </a>

            <a href="#" onclick="showSection('revenue', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-money-bill-wave mr-3"></i> Doanh Thu
            </a>
            <a href="#" onclick="showSection('settings', this)" class="nav-item block py-3 px-6 hover:bg-slate-700 transition duration-200 border-l-4 border-transparent">
                <i class="fa-solid fa-gear mr-3"></i> Cài Đặt
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 p-8">
        
        <div class="mb-8 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-700">Trang Quản Trị</h2>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Xin chào, <strong>Admin</strong></span>
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">A</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm min-h-[80vh]">

            <div id="dashboard" class="section active">
                <h3 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-2">Dashboard</h3>
                <p class="text-gray-600">Chào mừng bạn quay trở lại hệ thống quản trị.</p>
            </div>

            <div id="reports" class="section">
                <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">📊 Báo cáo & Thống kê</h3>
                        <p class="text-sm text-gray-500">Tổng hợp hiệu suất kinh doanh rạp chiếu phim</p>
                    </div>
                    <button onclick="exportReportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow flex items-center transition">
                        <i class="fa-solid fa-file-excel mr-2"></i> Xuất Báo Cáo Excel
                    </button>
                </div>

                <?php
                // 1. Dữ liệu tổng quan (Giữ nguyên của bạn)
                $res1 = $conn->query("SELECT SUM(total_amount) AS revenue, SUM(ticket_quantity) AS tickets FROM booking_history");
                $row1 = $res1->fetch_assoc();
                $totalRevenue = $row1['revenue'] ?? 0;
                $totalTickets = $row1['tickets'] ?? 0;

                $totalMovies = $conn->query("SELECT COUNT(*) AS total FROM movies")->fetch_assoc()['total'] ?? 0;
                $totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
                $totalGenres = $conn->query("SELECT COUNT(*) AS total FROM genre")->fetch_assoc()['total'] ?? 0;

                // 2. Dữ liệu Top 5 Phim Doanh Thu Cao Nhất (MỚI)
                $sqlTopMovies = "SELECT movie_title, SUM(total_amount) as total FROM booking_history GROUP BY movie_title ORDER BY total DESC LIMIT 5";
                $resTopMovies = $conn->query($sqlTopMovies);
                $topMoviesLabels = [];
                $topMoviesData = [];
                while ($row = $resTopMovies->fetch_assoc()) {
                    $topMoviesLabels[] = $row['movie_title'];
                    $topMoviesData[] = $row['total'];
                }

                // 3. Dữ liệu Doanh Thu Theo Tháng (Giữ nguyên)
                $sqlMonth = "SELECT DATE_FORMAT(booking_date, '%Y-%m') AS month, SUM(total_amount) AS revenue FROM booking_history GROUP BY month ORDER BY month ASC";
                $resMonth = $conn->query($sqlMonth);
                $months = [];
                $revenues = [];
                while ($row = $resMonth->fetch_assoc()) {
                    $months[] = $row['month'];
                    $revenues[] = $row['revenue'];
                }
                ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
                        <p class="text-gray-500 text-xs font-bold uppercase">Doanh thu</p>
                        <h4 class="text-xl font-bold text-green-600 mt-1"><?= number_format($totalRevenue, 0, ',', '.') ?> ₫</h4>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500">
                        <p class="text-gray-500 text-xs font-bold uppercase">Vé bán ra</p>
                        <h4 class="text-xl font-bold text-blue-600 mt-1"><?= number_format($totalTickets) ?></h4>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-cyan-500">
                        <p class="text-gray-500 text-xs font-bold uppercase">Số lượng phim</p>
                        <h4 class="text-xl font-bold text-cyan-600 mt-1"><?= $totalMovies ?></h4>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-purple-500">
                        <p class="text-gray-500 text-xs font-bold uppercase">Khách hàng</p>
                        <h4 class="text-xl font-bold text-purple-600 mt-1"><?= $totalUsers ?></h4>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
                        <p class="text-gray-500 text-xs font-bold uppercase">Thể loại</p>
                        <h4 class="text-xl font-bold text-yellow-600 mt-1"><?= $totalGenres ?></h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                        <h5 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fa-solid fa-chart-line mr-2 text-blue-500"></i> Xu hướng doanh thu
                        </h5>
                        <div class="h-64">
                            <canvas id="revenueReportChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                        <h5 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fa-solid fa-crown mr-2 text-yellow-500"></i> Top 5 Phim Doanh Thu Cao
                        </h5>
                        <div class="h-64">
                            <canvas id="topMoviesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 mb-8">
                    <h5 class="text-lg font-bold text-gray-700 mb-4">📝 Chi tiết giao dịch gần đây</h5>
                    <div class="overflow-x-auto max-h-80 overflow-y-auto">
                        <table class="w-full text-left border-collapse" id="reportTable">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-gray-600 text-sm font-bold border-b">Mã Đơn</th>
                                    <th class="px-4 py-3 text-gray-600 text-sm font-bold border-b">Phim</th>
                                    <th class="px-4 py-3 text-gray-600 text-sm font-bold border-b">Ngày đặt</th>
                                    <th class="px-4 py-3 text-gray-600 text-sm font-bold border-b">SL Vé</th>
                                    <th class="px-4 py-3 text-gray-600 text-sm font-bold border-b text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                $sqlDetails = "SELECT order_code, movie_title, booking_date, ticket_quantity, total_amount FROM booking_history ORDER BY booking_date DESC LIMIT 50";
                                $resDetails = $conn->query($sqlDetails);
                                if ($resDetails && $resDetails->num_rows > 0):
                                    while ($d = $resDetails->fetch_assoc()):
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-700 font-mono"><?= htmlspecialchars($d['order_code'] ?? 'N/A') ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?= htmlspecialchars($d['movie_title']) ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($d['booking_date'])) ?></td>
                                    <td class="px-4 py-2 text-sm text-center"><?= $d['ticket_quantity'] ?></td>
                                    <td class="px-4 py-2 text-sm text-right font-medium text-green-600"><?= number_format($d['total_amount']) ?> ₫</td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-gray-500">Chưa có dữ liệu</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

                <script>
                    // 1. Biểu đồ Doanh thu (Line Chart)
                    const ctxRev = document.getElementById('revenueReportChart').getContext('2d');
                    new Chart(ctxRev, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode($months) ?>,
                            datasets: [{
                                label: 'Doanh thu',
                                data: <?= json_encode($revenues) ?>,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    // 2. Biểu đồ Top Phim (Bar Chart) - MỚI
                    const ctxTop = document.getElementById('topMoviesChart').getContext('2d');
                    new Chart(ctxTop, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($topMoviesLabels) ?>,
                            datasets: [{
                                label: 'Doanh thu',
                                data: <?= json_encode($topMoviesData) ?>,
                                backgroundColor: [
                                    '#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4'
                                ],
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    // 3. Hàm Xuất Excel
                    function exportReportToExcel() {
                        // Lấy bảng dữ liệu HTML
                        var table = document.getElementById("reportTable");
                        
                        // Chuyển bảng thành WorkSheet
                        var wb = XLSX.utils.table_to_book(table, {sheet: "BaoCaoChiTiet"});
                        
                        // Xuất file
                        XLSX.writeFile(wb, 'BaoCao_DoanhThu_' + new Date().toISOString().slice(0,10) + '.xlsx');
                    }
                </script>
            </div>
            <div id="movies" class="section"><h3>Quản lý Phim</h3></div>
            <div id="tickets" class="section"><h3>Quản lý Vé</h3></div>
            <div id="seats" class="section"><h3>Quản lý Ghế</h3></div>
            <div id="shows" class="section"><h3>Quản lý Suất Chiếu</h3></div>
            <div id="users" class="section"><h3>Quản lý Người Dùng</h3></div>
            <div id="staff" class="section"><h3>Quản lý Nhân Viên</h3></div>
            <div id="genres" class="section"><h3>Quản lý Thể Loại</h3></div>
            <div id="revenue" class="section"><h3>Doanh Thu Chi Tiết</h3></div>
            <div id="settings" class="section"><h3>Cài Đặt</h3></div>

        </div>
    </main>

</div>

<script>
    function showSection(id, element) {
        // Ẩn tất cả section
        document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
        
        // Hiện section được chọn
        const target = document.getElementById(id);
        if(target) target.classList.add('active');

        // Reset style cho tất cả thẻ a trong sidebar
        document.querySelectorAll('.nav-item').forEach(a => {
            a.classList.remove('bg-blue-600', 'border-white');
            a.classList.add('border-transparent');
        });

        // Active style cho thẻ a được click (nếu có truyền element)
        if (element) {
            element.classList.remove('border-transparent');
            element.classList.add('bg-blue-600', 'border-white');
        }
    }
</script>

</body>
</html>