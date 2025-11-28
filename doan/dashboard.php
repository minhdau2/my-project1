<?php
session_start();
$msg = "";      
$msg_type = ""; 

if (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $msg_type = "error";
    unset($_SESSION['error']); 
} elseif (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $msg_type = "success";
    unset($_SESSION['success']); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Movie Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles.css" />

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
      .main {
        margin-left: 0;
        width: 100%;
      }
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: rgb(234, 233, 233);
      min-height: 100vh;
    }
    .sidebar {
      height: 300vh;
      background-color: #667eea;
      backdrop-filter: blur(10px);
      padding-top: 20px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      border-radius: 10px;
      transition: 0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,0.2);
    }
  </style>
</head>
<body class=" bg-gray-100 ">

  <div class="flex">
      <div class="sidebar w-60 bg-[#0d1b2a] text-white  p-2  w-full md:w-1/4 lg:w-1/6 space-y-1">
        <h2 class="text-center text-2xl font-bold mb-6 text-[#00aaff] ">ADMIN</h2>
          <a href="#" class="active px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('dashboard')">📊 Dashboard</a>
          <a href="#" class="px-4 py-3 rounded-md cursor-pointer" onclick="showSection('cinemas_rooms')">🏢 Quản lý Rạp & Phòng</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('movies')">🎥 Quản lý Phim</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('tickets')">🎟 Quản lý Vé</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('seats')">💺 Quản lý Ghế Ngồi</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('showtimes')">⏰ Quản lý Suất Chiếu</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('users')">👤 Quản lý Người Dùng</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('staffs')">🧍‍♂️ Quản lý Nhân Viên</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('reports')">📑 Báo Cáo</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer" onclick="showSection('revenue')">💵 Doanh Thu</a>
          <a href="#" class=" px-4 py-3 rounded-md  cursor-pointer">⚙️ Cài Đặt</a>
      </div>
      
      <div class="w-full md:w-3/4 lg:w-5/6 p-6">
        <div class="flex flex-col md:flex-row justify-between items-center bg-white rounded-2xl shadow-sm p-4 mb-6 animate-slide-down">
        
        <div class="mb-4 md:mb-0 w-full md:w-auto flex md:block justify-between items-center">
    
          <div>
              <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                  👋 Xin chào, 
                  <span class="text-blue-600">
                      <?php 
                      if(isset($_SESSION['admin'])) {
                          echo htmlspecialchars($_SESSION['admin']['name'] ?? 'Administrator');
                      } elseif(isset($_SESSION['user_name'])) {
                          echo htmlspecialchars($_SESSION['user_name']);
                      } else {
                          echo "Admin";
                      }
                      ?>
                  </span>
              </h2>
              <p class="text-sm text-gray-500 mt-1">
                  <i class="fa-regular fa-calendar mr-1"></i> 
                  Hôm nay là: <?php echo date("d/m/Y"); ?>
              </p>
          </div>

          <div class="md:hidden">
              <button onclick="toggleMobileMenu()" type="button" class="bg-purple-600 inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-purple-700 focus:outline-none shadow-md transition">
                  <span class="sr-only">Open main menu</span>
                  <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
              </button>
          </div>

      </div>

        <div class="flex items-center gap-4 w-full md:w-auto justify-end">
            
            

            <div class="h-8 w-px bg-gray-300 mx-1"></div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-gray-800">Quản Trị Viên</p>
                    <p class="text-xs text-green-500 font-medium">● Online</p>
                </div>
                
                <div class="relative group cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" 
                         alt="Admin Avatar" 
                         class="w-10 h-10 rounded-full border-2 border-white shadow-md">
                    
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 hidden group-hover:block border border-gray-100 z-50">
                        <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Đăng xuất
                        </a>
                    </div>
                </div>
                
            </div>
          
        </div>
        
    </div>
    <div id="mobile-menu" class="hidden md:hidden bg-white text-gray-800 border-t shadow-xl">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a href="#" class="active  rounded-md  cursor-pointer block px-3 py-2  text-base font-medium text-purple-600 bg-gray-100" onclick="showSection('dashboard')">📊 Dashboard</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('cinemas_rooms')">🏢 Quản lý Rạp & Phòng</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('movies')">🎥 Quản lý Phim</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('tickets')">🎟 Quản lý Vé</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('seats')">💺 Quản lý Ghế Ngồi</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('showtimes')">⏰ Quản lý Suất Chiếu</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('users')">👤 Quản lý Người Dùng</a>
          <a href="#" class=" rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('staffs')">🧍‍♂️ Quản lý Nhân Viên</a>
          <a href="#" class="rounded-md cursor-pointer block px-3 py-2  text-base font-medium hover:bg-gray-100" onclick="showSection('reports')">📑 Báo Cáo</a>
          
          
        </div>

        
      </div>



        <div id="dashboard" class="section">
          <div class="main w-full animate-slide-up">
              <h1 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                  <i class="fa-solid fa-chart-pie text-blue-600"></i> Dashboard
              </h1>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                  <?php
                  require_once 'config/db.php';
                  
                  $total_tickets = $conn->query("SELECT COUNT(id) as c FROM booking_history")->fetch_assoc()['c'] ?? 0;
                  $total_revenue = $conn->query("SELECT SUM(total_amount) as s FROM booking_history")->fetch_assoc()['s'] ?? 0;
                  $total_customers = $conn->query("SELECT COUNT(id) as c FROM users")->fetch_assoc()['c'] ?? 0;
                  $total_movies = $conn->query("SELECT COUNT(id) as c FROM movies")->fetch_assoc()['c'] ?? 0;
                  ?>

                  <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-blue-500 flex items-center justify-between hover:shadow-md transition">
                      <div>
                          <p class="text-gray-500 text-xs font-bold uppercase mb-1">Vé đã bán</p>
                          <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_tickets) ?></h3>
                      </div>
                      <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                          <i class="fa-solid fa-ticket text-xl"></i>
                      </div>
                  </div>

                  <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-green-500 flex items-center justify-between hover:shadow-md transition">
                      <div>
                          <p class="text-gray-500 text-xs font-bold uppercase mb-1">Tổng Doanh thu</p>
                          <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_revenue, 0, ',', '.') ?> <span class="text-sm text-gray-500">₫</span></h3>
                      </div>
                      <div class="bg-green-100 p-3 rounded-full text-green-600">
                          <i class="fa-solid fa-sack-dollar text-xl"></i>
                      </div>
                  </div>

                  <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-purple-500 flex items-center justify-between hover:shadow-md transition">
                      <div>
                          <p class="text-gray-500 text-xs font-bold uppercase mb-1">Khách hàng</p>
                          <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_customers) ?></h3>
                      </div>
                      <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                          <i class="fa-solid fa-users text-xl"></i>
                      </div>
                  </div>

                  <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-yellow-500 flex items-center justify-between hover:shadow-md transition">
                      <div>
                          <p class="text-gray-500 text-xs font-bold uppercase mb-1">Phim đang chiếu</p>
                          <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_movies) ?></h3>
                      </div>
                      <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                          <i class="fa-solid fa-film text-xl"></i>
                      </div>
                  </div>
              </div>

              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                  
                  <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                      <div class="flex justify-between items-center mb-4">
                          <h4 class="text-lg font-bold text-gray-700">📊 Thống kê theo thể loại</h4>
                      </div>
                      <div class="h-64">
                          <canvas id="mostLikedChart"></canvas>
                      </div>
                  </div>

                  <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                      <h4 class="text-lg font-bold text-gray-700 mb-4">🎬 Lịch chiếu hôm nay</h4>
                      <div class="space-y-4 overflow-y-auto max-h-64 pr-2">
                          <?php
                          $today = date('Y-m-d');
                          // Lấy các suất chiếu trong ngày
                          $sqlToday = "
                              SELECT s.time, m.title, r.name as room_name 
                              FROM showtimes s 
                              JOIN movies m ON s.movie_id = m.id 
                              JOIN rooms r ON s.room_id = r.id
                              WHERE s.date = '$today' 
                              ORDER BY s.time ASC LIMIT 5
                          ";
                          $resToday = $conn->query($sqlToday);
                          
                          if($resToday && $resToday->num_rows > 0):
                              while($show = $resToday->fetch_assoc()):
                                  // Format giờ (bỏ giây)
                                  $timeShow = date('H:i', strtotime($show['time']));
                          ?>
                              <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-blue-50 transition">
                                  <div class="bg-blue-100 text-blue-600 font-bold px-3 py-2 rounded text-sm">
                                      <?= $timeShow ?>
                                  </div>
                                  <div>
                                      <h5 class="text-sm font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($show['title']) ?></h5>
                                      <p class="text-xs text-gray-500"><i class="fa-solid fa-door-open"></i> <?= htmlspecialchars($show['room_name']) ?></p>
                                  </div>
                              </div>
                          <?php endwhile; else: ?>
                              <p class="text-gray-500 text-sm text-center py-4">Hôm nay không có lịch chiếu.</p>
                          <?php endif; ?>
                      </div>
                      <div class="mt-4 text-center">
                          <button onclick="showSection('showtimes')" class="text-blue-600 text-sm font-medium hover:underline">Xem tất cả lịch chiếu &rarr;</button>
                      </div>
                  </div>
              </div>

              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  
                  <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                      <div class="flex justify-between items-center mb-4">
                          <h4 class="text-lg font-bold text-gray-700">🛒 Giao dịch mới nhất</h4>
                          <button onclick="showSection('tickets')" class="text-blue-600 text-sm hover:underline">Xem tất cả</button>
                      </div>
                      <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Phim</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">SL Vé</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php
                                // Lấy 5 đơn hàng mới nhất (Query giữ nguyên)
                                $sqlRecent = "
                                    SELECT u.name as user_name, b.movie_title, b.ticket_quantity, b.total_amount, b.booking_date 
                                    FROM booking_history b
                                    JOIN users u ON b.user_id = u.id
                                    ORDER BY b.booking_date DESC LIMIT 5
                                ";
                                $resRecent = $conn->query($sqlRecent);
                                
                                if($resRecent && $resRecent->num_rows > 0):
                                    while($order = $resRecent->fetch_assoc()):
                                ?>
                                <tr class="hover:bg-blue-50 transition duration-150">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            
                                            <div class="ml-3">
                                                <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($order['user_name']) ?></p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="text-sm text-gray-600 font-medium line-clamp-1 max-w-[150px]" title="<?= htmlspecialchars($order['movie_title']) ?>">
                                            <?= htmlspecialchars($order['movie_title']) ?>
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                            <?= $order['ticket_quantity'] ?> vé
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-emerald-600">
                                            <?= number_format($order['total_amount']) ?> ₫
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right whitespace-nowrap text-xs text-gray-400">
                                        <div class="flex flex-col items-end">
                                            <span><?= date('H:i', strtotime($order['booking_date'])) ?></span>
                                            <span><?= date('d/m/Y', strtotime($order['booking_date'])) ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="5" class="text-center py-6 text-gray-500 italic">Chưa có giao dịch nào gần đây.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                  </div>

                  <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                      <h4 class="text-lg font-bold text-gray-700 mb-4">⚡ Trạng thái hệ thống</h4>
                      <div class="grid grid-cols-2 gap-4">
                          <div class="bg-indigo-50 p-4 rounded-xl text-center hover:bg-indigo-100 transition cursor-pointer" onclick="showSection('users')">
                              <div class="text-indigo-600 mb-2"><i class="fa-solid fa-user-plus text-2xl"></i></div>
                              <div class="text-2xl font-bold text-gray-800"><?= $total_customers ?></div>
                              <div class="text-xs text-gray-500">User mới</div>
                          </div>
                          <div class="bg-pink-50 p-4 rounded-xl text-center hover:bg-pink-100 transition cursor-pointer" onclick="showSection('comments')">
                              <div class="text-pink-600 mb-2"><i class="fa-solid fa-star text-2xl"></i></div>
                              <div class="text-2xl font-bold text-gray-800">--</div> <div class="text-xs text-gray-500">Đánh giá mới</div>
                          </div>
                          <div class="bg-orange-50 p-4 rounded-xl text-center hover:bg-orange-100 transition cursor-pointer" onclick="showSection('cinemas_rooms')">
                              <?php 
                                  $total_rooms = $conn->query("SELECT COUNT(id) as c FROM rooms")->fetch_assoc()['c'] ?? 0; 
                              ?>
                              <div class="text-orange-600 mb-2"><i class="fa-solid fa-door-open text-2xl"></i></div>
                              <div class="text-2xl font-bold text-gray-800"><?= $total_rooms ?></div>
                              <div class="text-xs text-gray-500">Phòng chiếu</div>
                          </div>
                          <div class="bg-cyan-50 p-4 rounded-xl text-center hover:bg-cyan-100 transition cursor-pointer">
                              <div class="text-cyan-600 mb-2"><i class="fa-solid fa-server text-2xl"></i></div>
                              <div class="text-sm font-bold text-green-600">Online</div>
                              <div class="text-xs text-gray-500">Server Status</div>
                          </div>
                      </div>
                  </div>

              </div>
          </div>
      </div>
        <?php
          require 'config/db.php';
          $genres = [];
          $result = $conn->query("SELECT name FROM genre ");
          if ($result) {
            $genres = $result->fetch_all(MYSQLI_ASSOC);
          }
          ?>
        <div id="cinemas_rooms" class="section hidden">
    <h2 class="text-xl font-bold mb-6">🏢 Quản lý Rạp & Phòng Chiếu</h2>
    <?php if ($msg != ""): ?>
                  <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 ">
                      <strong class="font-bold">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                      </strong>
                      <span class="block sm:inline"><?php echo $msg; ?></span>
                  </div>
              <?php endif; ?>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-2xl shadow-md p-6 animate-slide-up">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-700">Danh sách Rạp</h3>
                <button onclick="document.getElementById('addCinemaModal').classList.remove('hidden'); document.getElementById('addCinemaModal').classList.add('flex');" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fa-solid fa-plus"></i> Thêm Rạp
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-3 py-2">Tên Rạp</th>
                            <th class="px-3 py-2">Địa chỉ / SĐT</th>
                            <th class="px-3 py-2 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        require_once 'config/db.php';
                        $sqlCinemas = "SELECT * FROM cinemas ORDER BY id DESC";
                        $resCinemas = $conn->query($sqlCinemas);
                        if($resCinemas && $resCinemas->num_rows > 0):
                            while($c = $resCinemas->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-medium text-blue-900"><?= htmlspecialchars($c['name']) ?></td>
                            <td class="px-3 py-2 text-gray-500">
                                <div class="text-xs"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($c['address']) ?></div>
                                <div class="text-xs mt-1"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($c['phone']) ?></div>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <button onclick="openEditCinemaModal(<?= $c['id'] ?>, '<?= addslashes($c['name']) ?>', '<?= addslashes($c['address']) ?>', '<?= addslashes($c['phone']) ?>')" 
                                        class="text-blue-500 hover:text-blue-700 mr-2"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button onclick="openDeleteCinemaModal(<?= $c['id'] ?>)" 
                                        class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-700">Danh sách Phòng</h3>
                <button onclick="document.getElementById('addRoomModal').classList.remove('hidden'); document.getElementById('addRoomModal').classList.add('flex');" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fa-solid fa-plus"></i> Thêm Phòng
                </button>
            </div>

            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase sticky top-0">
                        <tr>
                            <th class="px-3 py-2">Tên Phòng</th>
                            <th class="px-3 py-2">Thuộc Rạp</th>
                            <th class="px-3 py-2 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $sqlRooms = "SELECT r.id, r.name, r.cinema_id, c.name as cinema_name 
                                     FROM rooms r 
                                     JOIN cinemas c ON r.cinema_id = c.id 
                                     ORDER BY c.name, r.name";
                        $resRooms = $conn->query($sqlRooms);
                        if($resRooms && $resRooms->num_rows > 0):
                            while($r = $resRooms->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-bold text-gray-700"><?= htmlspecialchars($r['name']) ?></td>
                            <td class="px-3 py-2 text-gray-500 text-xs"><?= htmlspecialchars($r['cinema_name']) ?></td>
                            <td class="px-3 py-2 text-right">
                                <button onclick="openEditRoomModal(
                                    <?= $r['id'] ?>, 
                                    '<?= addslashes($r['name']) ?>', 
                                    <?= $r['cinema_id'] ?>, 
                                    '<?= addslashes($r['cinema_name']) ?>' /* <-- THÊM TÊN RẠP VÀO ĐÂY */
                                )" class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="openDeleteRoomModal(<?= $r['id'] ?>)" 
                                        class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
        <div id="movies" class="section hidden ">
            <h2 class="text-xl font-bold mb-4">🎥 Quản lý Phim</h2>
              <div class="bg-white rounded-2xl shadow-md p-6 mb-10 animate-slide-up">
                <div class="flex justify-between items-center mb-6">
                  <h2 class="text-xl font-semibold text-gray-700">Thêm Phim</h2>
                  
                </div>

                <form action="add_movie.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-5 gap-5">
                  <div class="col-span-1 lg:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-1 md:col-span-2">
                      <input 
                        type="text" 
                        name="title" 
                        placeholder="Tên Phim" 
                        required
                        class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" 
                      />
                    </div>
                    <div>
                      <input 
                        type="text" 
                        name="duration" 
                        placeholder="Thời lượng (phút)" 
                        required
                        class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" 
                      />
                    </div>

                    <div>
                      <input 
                        type="number" 
                        name="price" 
                        placeholder="Giá vé" 
                        required
                        class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" 
                      />
                    </div>

                    <div class="col-span-1 md:col-span-2">
                      <textarea
                        name="description"
                        placeholder="Mô tả phim..."
                        rows="3"
                        class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full resize-none"
                      ></textarea>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Thể loại (Tối đa 2)</label>
                      
                      <div id="genre-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-40 overflow-y-auto p-2  rounded-lg bg-gray-50 mb-3">
                          <?php foreach ($genres as $g): ?>
                              <label class="flex items-center gap-2 bg-white px-2 py-1 rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                  <input type="checkbox" name="genre[]" 
                                        value="<?= htmlspecialchars($g['name']) ?>" 
                                        class="genre-checkbox rounded text-blue-600 focus:ring-blue-500">
                                  <span class="text-sm text-gray-700 select-none"><?= htmlspecialchars($g['name']) ?></span>
                              </label>
                          <?php endforeach; ?>
                      </div>

                      <div class="flex gap-2 items-center">
                        <input type="text" id="newGenreName" placeholder="Thêm thể loại..." 
                              class="w-48 border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        
                        <button type="button" onclick="addNewGenreAjax()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                      
                      <p id="genre-warning" class="text-red-500 text-xs mt-1 hidden">
                          <i class="fa-solid fa-circle-exclamation"></i> Bạn chỉ được chọn tối đa 2 thể loại.
                      </p>
                  </div>

                  </div>

                  <div class="col-span-1 lg:col-span-1 flex flex-col items-center gap-2">
                    <input 
                      type="file" 
                      name="poster" 
                      id="posterInput"
                      accept="image/*"
                      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" 
                    />
                    <img 
                      id="posterPreview" 
                      src="#" 
                      alt="Xem trước poster" 
                      class="hidden w-40 h-56 object-cover rounded-lg shadow border"
                    />
                  </div>

                  <div class="col-span-1 lg:col-span-5 flex justify-end">
                    <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 shadow">
                      <i class="fa-solid fa-plus"></i> Thêm Phim
                    </button>
                  </div>

                </form>


              </div>


              <div class="bg-white rounded-2xl shadow-md p-6 animate-zoom-in">
              <?php if ($msg != ""): ?>
                  <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 animate-bounce">
                      <strong class="font-bold">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                      </strong>
                      <span class="block sm:inline"><?php echo $msg; ?></span>
                  </div>
              <?php endif; ?>
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold text-gray-700">Danh sách phim</h2>
                  <p id="movieCount" class="text-gray-500 text-sm">6 Phim</p>
                </div>
                <div class="overflow-x-auto rounded-xl">
                  <table class="w-full text-left border-collapse">
                    <thead >
                      <tr class=" bg-gray-100 text-gray-600 text-sm uppercase tracking-wide  ">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Tên phim</th>
                        <th class="px-4 py-2">Thể loại</th>
                        <th class="px-4 py-2">Thời lượng</th>
                        <th class="px-4 py-2">Giá</th>
                        <th class="px-4 py-2">Mô tả</th>
                        <th class="px-4 py-2">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                      <?php
                        require_once 'config/db.php';
                        $sql = "SELECT id, title, genre, duration, price, description FROM movies";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['id']) .'</td>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['title']) .'</td>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['genre']) .'</td>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['duration']) .'</td>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['price']) .'</td>';
                                echo '<td class="px-4 py-2">'. htmlspecialchars($row['description']) .'</td>';
                                echo '<td class="px-4 py-2">
                                        <button type="button" class="px-2 py-1  hover:scale-110"
                                          onclick="openEditModalMovies(' . $row['id'] . ', \'' . addslashes($row['title']) . '\', \'' . addslashes($row['genre']) . '\', \'' . addslashes($row['duration']) . '\', \'' . addslashes($row['price']) . '\', \'' . addslashes($row['description']) . '\')">
                                          <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                                        </button>
                                        <button type="button" class="px-2 py-1  hover:scale-110"
                                          onclick="openDeleteModalMovies(' . $row['id'] . ')">
                                          <i class="fa-solid fa-trash text-red-500"></i>
                                        </button>
                                      </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center px-4 py-2">Không có phim để hiển thị.</td></tr>';
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>

        <div id="tickets" class="section hidden">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
              <div>
                  <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                      🎟 Quản lý vé
                      <span class="text-sm font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                          </span>
                  </h2>
                  <p class="text-sm text-gray-500 hidden md:block">Xem và quản lý danh sách vé đã đặt</p>
              </div>

              <form method="GET" class="flex w-full md:w-auto gap-2">
                  <?php
                  require_once 'config/db.php';
                  $movies_sql = "SELECT DISTINCT movie_title FROM booking_history";
                  $movies_result = $conn->query($movies_sql);
                  $selected_movie = isset($_GET['movie_title']) ? $_GET['movie_title'] : '';
                  ?>
                  <select name="movie_title" class="flex-1 md:flex-none border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                      <option value="">-- Tất cả phim --</option>
                      <?php
                      if ($movies_result && $movies_result->num_rows > 0) {
                          while ($m = $movies_result->fetch_assoc()) {
                              $selected = ($selected_movie == $m['movie_title']) ? 'selected' : '';
                              echo '<option value="' . htmlspecialchars($m['movie_title']) . '" ' . $selected . '>' . htmlspecialchars($m['movie_title']) . '</option>';
                          }
                      }
                      ?>
                  </select>
                  <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm whitespace-nowrap">
                      <i class="fa-solid fa-filter"></i> Lọc
                  </button>
              </form>
          </div>

          <div class="bg-white rounded-2xl shadow-md p-4 md:p-6 mb-10 animate-slide-up">
              <div class="animate-zoom-in">
                  <?php if ($msg != ""): ?>
                      <div class="<?php echo ($msg_type == 'success') ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'; ?> border px-4 py-3 rounded-lg relative mb-4 animate-bounce flex items-center gap-2">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <div>
                              <strong class="font-bold"><?php echo ($msg_type == 'success') ? 'Thành công!' : 'Lỗi!'; ?></strong>
                              <span class="block sm:inline"><?php echo $msg; ?></span>
                          </div>
                      </div>
                  <?php endif; ?>

                  <?php
                  $sql = "SELECT * FROM booking_history";
                  if (!empty($selected_movie)) {
                      $sql .= " WHERE movie_title = '" . $conn->real_escape_string($selected_movie) . "'";
                  }
                  $result = $conn->query($sql);
                  ?>

                  <div class="overflow-hidden">
                      
                      <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-100">
                          <table class="w-full text-left border-collapse">
                              <thead class="bg-gray-50">
                                  <tr>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">ID</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Phim</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Người đặt</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Thời gian</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Ghế</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">Tổng tiền</th>
                                      <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-center">Thao tác</th>
                                  </tr>
                              </thead>
                              <tbody class="divide-y divide-gray-100">
                                  <?php
                                  if ($result && $result->num_rows > 0) {
                                      $rows = $result->fetch_all(MYSQLI_ASSOC);
                                      foreach ($rows as $row) {
                                  ?>
                                      <tr class="hover:bg-gray-50 transition">
                                          <td class="px-4 py-3 font-mono text-sm text-gray-500">#<?= $row['id'] ?></td>
                                          <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($row['movie_title']) ?></td>
                                          <td class="px-4 py-3 text-sm text-gray-600">
                                              <div class="flex items-center gap-2">
                                                  <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                                      <i class="fa-solid fa-user"></i>
                                                  </div>
                                                  User ID: <?= $row['user_id'] ?>
                                              </div>
                                          </td>
                                          <td class="px-4 py-3 text-sm text-gray-600">
                                              <div><?= date('d/m/Y', strtotime($row['show_date'])) ?></div>
                                              <div class="text-xs text-gray-400"><?= date('H:i', strtotime($row['show_time'])) ?></div>
                                          </td>
                                          <td class="px-4 py-3 text-sm">
                                              <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold border border-gray-200">
                                                  <?= htmlspecialchars($row['seats']) ?>
                                              </span>
                                          </td>
                                          <td class="px-4 py-3 text-right font-bold text-green-600"><?= number_format($row['total_amount']) ?> ₫</td>
                                          <td class="px-4 py-3 text-center">
                                              <div class="flex justify-center gap-2">
                                                  <button type="button" class="text-blue-500 hover:bg-blue-50 p-2 rounded-full transition"
                                                      onclick="openDetailModal(<?= htmlspecialchars($row['id']) ?>, '<?= addslashes($row['movie_title']) ?>', '<?= addslashes($row['show_date']) ?>', '<?= addslashes($row['show_time']) ?>', '<?= addslashes($row['seats']) ?>', '<?= addslashes($row['ticket_quantity']) ?>', '<?= addslashes($row['ticket_price']) ?>', '<?= addslashes($row['total_amount']) ?>', '<?= addslashes($row['booking_date']) ?>', '<?= addslashes($row['order_code']) ?>', '<?= addslashes($row['user_type']) ?>')">
                                                      <i class="fa-solid fa-eye"></i>
                                                  </button>
                                                  
                                              </div>
                                          </td>
                                      </tr>
                                  <?php 
                                      }
                                  } else {
                                      echo '<tr><td colspan="7" class="text-center py-8 text-gray-500 italic">Chưa có dữ liệu vé nào.</td></tr>';
                                  }
                                  ?>
                              </tbody>
                          </table>
                      </div>

                      <div class="md:hidden space-y-4">
                          <?php
                          if (isset($rows) && count($rows) > 0) {
                              foreach ($rows as $row) {
                          ?>
                              <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm">
                                  <div class="flex justify-between items-start mb-3 border-b border-gray-200 pb-3">
                                      <div>
                                          <h4 class="font-bold text-gray-800 text-lg line-clamp-1"><?= htmlspecialchars($row['movie_title']) ?></h4>
                                          <span class="text-xs text-gray-400 font-mono">Mã đơn: <?= htmlspecialchars($row['order_code']) ?></span>
                                      </div>
                                      <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">#<?= $row['id'] ?></span>
                                  </div>
                                  
                                  <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-600 mb-4">
                                      <div class="flex items-center gap-2">
                                          <i class="fa-regular fa-calendar text-gray-400 w-4"></i>
                                          <?= date('d/m/Y', strtotime($row['show_date'])) ?>
                                      </div>
                                      <div class="flex items-center gap-2">
                                          <i class="fa-regular fa-clock text-gray-400 w-4"></i>
                                          <?= date('H:i', strtotime($row['show_time'])) ?>
                                      </div>
                                      <div class="flex items-center gap-2 col-span-2">
                                          <i class="fa-solid fa-chair text-gray-400 w-4"></i>
                                          Ghế: <span class="font-bold text-gray-800"><?= htmlspecialchars($row['seats']) ?></span>
                                      </div>
                                      <div class="flex items-center gap-2 col-span-2">
                                          <i class="fa-solid fa-user text-gray-400 w-4"></i>
                                          Khách: User ID <?= $row['user_id'] ?>
                                      </div>
                                  </div>

                                  <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                      <span class="text-green-600 font-bold text-lg">
                                          <?= number_format($row['total_amount']) ?> ₫
                                      </span>
                                      <div class="flex gap-2">
                                          <button class="bg-white border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-50 text-sm"
                                              onclick="openDetailModal(<?= htmlspecialchars($row['id']) ?>, '<?= addslashes($row['movie_title']) ?>', '<?= addslashes($row['show_date']) ?>', '<?= addslashes($row['show_time']) ?>', '<?= addslashes($row['seats']) ?>', '<?= addslashes($row['ticket_quantity']) ?>', '<?= addslashes($row['ticket_price']) ?>', '<?= addslashes($row['total_amount']) ?>', '<?= addslashes($row['booking_date']) ?>', '<?= addslashes($row['order_code']) ?>', '<?= addslashes($row['user_type']) ?>')">
                                              Chi tiết
                                          </button>
                                          
                                      </div>
                                  </div>
                              </div>
                          <?php 
                              }
                          } else {
                              echo '<div class="text-center py-10 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">Chưa có dữ liệu</div>';
                          }
                          ?>
                      </div>

                  </div>
              </div>
          </div>
      </div>
        <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 w-96 relative shadow-lg">
            <div class="flex justify-between items-center mb-2">
              <h3 class="text-lg font-semibold mb-4">Chi tiết vé</h3>
              <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                ✖
              </button>
            </div>
            <div id="ticketDetailContent" class="space-y-2 text-sm"></div>                    
          </div>
        </div>
        
        <div id="seats" class="section hidden ">
            <h2 class="text-xl font-bold mb-4">💺 Quản lý Ghế Ngồi</h2>
              


              <div class="bg-white rounded-2xl shadow-md p-6 animate-zoom-in">
              <?php if ($msg != ""): ?>
                  <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 animate-bounce">
                      <strong class="font-bold">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                      </strong>
                      <span class="block sm:inline"><?php echo $msg; ?></span>
                  </div>
              <?php endif; ?>
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold text-gray-700">Danh sách ghế ngồi</h2>
                </div>
                <div class="overflow-x-auto rounded-xl">
                  <div class="grid grid-cols-1 md:grid-cols-[1.3fr_0.7fr] gap-6">
                    <div class="bg-white rounded-xl  p-6 scroll-animate-right">
                      <h3 class="text-xl font-bold mb-4">🎬 Chọn Thông Tin Suất Chiếu</h3>

                      <div class="grid gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <select id="cinema-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadRooms()">
                            <option value="">Chọn rạp</option>
                            <?php
                            require_once 'config/db.php';
                            $sql = "SELECT id, name FROM cinemas";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                          </select>
                          <select id="room-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadMovies()">
                            <option value="">Chọn phòng</option>
                          </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                          <select id="movie-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadDates()">
                            <option value="">Chọn phim</option>
                            <?php
                            require_once 'config/db.php';
                            $sql = "SELECT id, title FROM movies";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                            }
                            
                            ?>
                          </select>
                          <select id="date-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadShowtimes()">
                            <option value="">Chọn ngày chiếu</option>
                            <?php
                            if (isset($_GET['movieId'])) {
                                $movieId = intval($_GET['movieId']);
                                $sqlDates = "SELECT DISTINCT DATE(time) as show_date FROM showtimes WHERE movie_id = $movieId ORDER BY show_date";
                                $resultDates = $conn->query($sqlDates);
                                while ($rowDate = $resultDates->fetch_assoc()) {
                                    $selected = (isset($_GET['date']) && $_GET['date'] == $rowDate['show_date']) ? "selected" : "";
                                    echo "<option value='{$rowDate['show_date']}' $selected>{$rowDate['show_date']}</option>";
                                }
                            }
                            ?>
                          </select>
                          <select id="showtime-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadSeats()">
                            <option value="">Chọn suất chiếu</option>
                            <?php
                            if (isset($_GET['movieId']) && isset($_GET['date'])) {
                                $movieId = intval($_GET['movieId']);
                                $date = $_GET['date'];
                                $sqlShowtimes = "SELECT id, TIME(time) as show_time FROM showtimes 
                                                WHERE movie_id = $movieId AND DATE(time) = '$date'";
                                $resultShowtimes = $conn->query($sqlShowtimes);
                                while ($rowShowtime = $resultShowtimes->fetch_assoc()) {
                                    $selected = (isset($_GET['showtimeId']) && $_GET['showtimeId'] == $rowShowtime['id']) ? "selected" : "";
                                    echo "<option value='{$rowShowtime['id']}' $selected>{$rowShowtime['show_time']}</option>";
                                }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="bg-white rounded-xl  p-6 scroll-animate-left">
                      <h3 class="text-xl font-bold mb-4">💺 Chọn Ghế Ngồi</h3>

                      <div class="mb-4">
                        <div class="screen"></div>
                      </div>

                      <form id="seat-form" method="POST">
                        <input type="hidden" name="movie_id" id="movie_id" value="">
                        <input type="hidden" name="date" id="date" value="">
                        <input type="hidden" name="showtimeId" id="showtimeId" value="">

                        <div id="seat-map" class="flex flex-col items-center space-y-2"></div>
                      </form>
                      <div class="flex justify-center space-x-6 mt-6 text-sm">
                          <div class="flex items-center">
                              <div class="seat  mr-2 bg-gray-400"></div>
                              <span>Ghế bị hỏng</span>
                          </div>
                      </div>
                    </div>
                  </div>


                </div>
              </div>
          </div>

        <div id="users" class="section hidden">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">👤 Quản lý Người Dùng</h2>
          <div class="flex gap-2">
              <a href="export_users.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                  <i class="fa-solid fa-file-export"></i> Xuất Excel
              </a>
              
              <button onclick="openImportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                  <i class="fa-solid fa-file-import"></i> Nhập Excel
              </button>
          </div>
      </div>
          <div class="bg-white rounded-2xl shadow-md p-6 animate-slide-up">
            <div class="overflow-x-auto rounded-xl">
            <?php if ($msg != ""): ?>
              <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 ">
                  <strong class="font-bold">
                      <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                      <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                  </strong>
                  <span class="block sm:inline"><?php echo $msg; ?></span>
              </div>
          <?php endif; ?>
              <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                  <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wide">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Tên người dùng</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Số điện thoại</th>
                    <th class="px-4 py-2">Trạng thái</th> 
                    <th class="px-4 py-2">Thao tác</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <?php
                    require_once 'config/db.php';
                    $sql = "SELECT id, name, email, phone, password, created_at, status FROM users";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $statusLabel = ($row['status'] == 1) 
                              ? '<span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-400 shadow-sm">Hoạt động</span>' 
                              : '<span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-400 shadow-sm">Đã khóa</span>';

                            echo '<tr>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['id']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['name']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['email']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['phone']) .'</td>';
                            echo '<td class="px-4 py-2">'. $statusLabel .'</td>'; // MỚI
                            
                            echo '<td class="px-4 py-2">
                                    <button type="button" class="px-2 py-1 hover:scale-110"
                                      onclick="openEditModal(' . $row['id'] . ', \'' . addslashes($row['name']) . '\', \'' . addslashes($row['email']) . '\', \'' . addslashes($row['phone']) . '\', ' . $row['status'] . ')"> 
                                      <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                                    </button>
                                    <button type="button" class="px-2 py-1 hover:scale-110"
                                      onclick="openDeleteModal(' . $row['id'] . ')">
                                      <i class="fa-solid fa-trash text-red-500"></i>
                                    </button>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center px-4 py-2">Không có người dùng để hiển thị.</td></tr>';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div id="staffs" class="section hidden">
        <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'Master'): ?>
        <div class="flex justify-between items-center mb-6 animate-slide-up">
          <h2 class="text-xl font-bold mb-4">🧍‍♂️ Quản lý Nhân Viên</h2>
          <div class="flex gap-2">
              <a href="export_staff.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                  <i class="fa-solid fa-file-export"></i> Xuất Excel
              </a>
              <button onclick="openImportStaffModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                  <i class="fa-solid fa-file-import"></i> Nhập Excel
              </button>
          </div>
      </div>
        <div class="bg-white rounded-2xl shadow-md p-6 mb-10 animate-slide-up">
                <div class="flex justify-between items-center mb-6">
                  <h2 class="text-xl font-semibold text-gray-700">Thêm Nhân Viên</h2>
                </div>
                <?php if ($msg != ""): ?>
                  <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 animate-bounce">
                      <strong class="font-bold">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                      </strong>
                      <span class="block sm:inline"><?php echo $msg; ?></span>
                  </div>
              <?php endif; ?>
              <?php
                $existing_roles = ['Staff', 'Master']; 
                $sqlGetRoles = "SELECT DISTINCT role FROM admin";
                $resRoles = $conn->query($sqlGetRoles);

                if ($resRoles) {
                    while ($r = $resRoles->fetch_assoc()) {
                        if (!empty($r['role']) && !in_array($r['role'], $existing_roles)) {
                            $existing_roles[] = $r['role'];
                        }
                    }
                }
                ?>

                <form action="add_staff.php" method="POST" class="grid md:grid-cols-3 lg:grid-cols-5 gap-5">
                  <input type="text" name="name" placeholder="Tên nhân viên" required
                      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" />

                  <input type="email" name="email" placeholder="Email" required
                      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" />

                  <input type="text" name="phone" placeholder="Số điện thoại" required
                      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" />

                  <input type="text" name="password" placeholder="Mật Khẩu" required
                      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition w-full" />

                  <div class="flex gap-2">
                      <select id="roleSelect" name="role" class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 w-full transition">
                        <?php foreach ($existing_roles as $r): ?>
                            <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                        <?php endforeach; ?>
                    </select>

                      <input type="text" id="roleInput" name="role" placeholder="Nhập vai trò mới..." disabled
                          class="hidden border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 w-full transition" />

                      <button type="button" onclick="toggleRoleModeAdd()"
                          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 rounded-lg border border-gray-300"
                          title="Thêm vai trò khác">
                          <i class="fa-solid fa-plus" id="roleIcon"></i>
                      </button>
                  </div>

                  <div class="md:col-span-3 lg:col-span-5 flex justify-end">
                      <button type="submit"
                          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 shadow">
                          <i class="fa-solid fa-plus"></i> Thêm
                      </button>
                  </div>
              </form>
              </div>
          <div class="bg-white rounded-2xl shadow-md p-6 animate-slide-up">
            <div class="overflow-x-auto rounded-xl">
              <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                  <tr class=" bg-gray-100 text-gray-600 text-sm uppercase tracking-wide ">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Tên nhân viên</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Số điện thoại</th>
                    <th class="px-4 py-2">Vai trò</th>
                    <th class="px-4 py-2">Thao tác</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <?php
                    require_once 'config/db.php';
                    $sql = "SELECT id, name, email, phone, created_at, role FROM admin";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['id']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['name']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['email']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['phone']) .'</td>';
                            echo '<td class="px-4 py-2">'. htmlspecialchars($row['role']) .'</td>';
                            echo '<td class="px-4 py-2">
                                    <button type="button" class="px-2 py-1  hover:scale-110"
                                      onclick="openEditModalStaff(' . $row['id'] . ', \'' . addslashes($row['name']) . '\', \'' . addslashes($row['email']) . '\', \'' . addslashes($row['phone']) . '\', \'' . addslashes($row['role']) . '\')">
                                      <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                                    </button>
                                    <button type="button" class="px-2 py-1  hover:scale-110"
                                      onclick="openDeleteModalStaff(' . $row['id'] . ')">
                                      <i class="fa-solid fa-trash text-red-500"></i>
                                    </button>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center px-4 py-2">Không có nhân viên để hiển thị.</td></tr>';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php else: ?>
            <div class="flex flex-col items-center justify-center h-full text-center py-20">
                <i class="fa-solid fa-shield-halved text-6xl text-red-500 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800">Truy cập bị từ chối</h2>
                <p class="text-gray-600 mt-2">Bạn không có quyền truy cập mục này. Chỉ <strong>Master</strong> mới được phép quản lý nhân sự.</p>
            </div>
        <?php endif; ?>
      </div>


      <div id="showtimes" class="section hidden">
        <h2 class="text-xl font-bold mb-4">🎬 Quản lý Suất Chiếu</h2>
        <div class="bg-white rounded-2xl shadow-md p-6 mb-10 animate-slide-up">
                <div class="flex justify-between items-center mb-6">
                  <h2 class="text-xl font-semibold text-gray-700">Thêm suất chiếu </h2>
                </div>
                <?php if ($msg != ""): ?>
                  <div class="<?php echo ($msg_type == 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4 animate-bounce">
                      <strong class="font-bold">
                          <i class="fa-solid <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?>"></i>
                          <?php echo ($msg_type == 'success') ? 'Thành công:' : 'Lỗi:'; ?>
                      </strong>
                      <span class="block sm:inline"><?php echo $msg; ?></span>
                  </div>
              <?php endif; ?>
                  <?php
            require 'config/db.php';
            $cinemas = $conn->query("SELECT id, name FROM cinemas ORDER BY name");
            $selectedCinema = isset($_GET['cinema_id']) ? intval($_GET['cinema_id']) : 0;
            $selectedRoom   = isset($_GET['room_id'])   ? intval($_GET['room_id'])   : 0;
            $selectedMovie  = isset($_GET['movie_id'])  ? intval($_GET['movie_id'])  : 0;
            $rooms = [];
            if ($selectedCinema) {
              $stmt = $conn->prepare("SELECT id, name FROM rooms WHERE cinema_id = ? ORDER BY name");
              $stmt->bind_param("i", $selectedCinema);
              $stmt->execute();
              $rooms = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
              $stmt->close();
            }
            $movies = [];
            if ($selectedRoom) {
              $stmt = $conn->prepare("
                  SELECT DISTINCT m.id, m.title
                  FROM showtimes s
                  JOIN movies m ON s.movie_id = m.id
                  WHERE s.room_id = ?
                  ORDER BY m.title
              ");
              $stmt->bind_param("i", $selectedRoom);
              $stmt->execute();
              $movies = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
              $stmt->close();
            }

            $showtimes = [];
            if ($selectedRoom) {
              if ($selectedMovie) {
                $stmt = $conn->prepare("
                  SELECT s.*, m.title AS movie_title, c.name AS cinema_name, r.name AS room_name
                  FROM showtimes s
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  JOIN cinemas c ON r.cinema_id = c.id
                  WHERE s.room_id = ? AND s.movie_id = ?
                  ORDER BY s.date, s.time
                ");
                $stmt->bind_param("ii", $selectedRoom, $selectedMovie);
              } else {
                $stmt = $conn->prepare("
                  SELECT s.*, m.title AS movie_title, c.name AS cinema_name, r.name AS room_name
                  FROM showtimes s
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  JOIN cinemas c ON r.cinema_id = c.id
                  WHERE s.room_id = ?
                  ORDER BY s.date, s.time
                ");
                $stmt->bind_param("i", $selectedRoom);
              }
              $stmt->execute();
              $showtimes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
              $stmt->close();
            }
            ?>
                <form action="add_showtime.php" method="POST" class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 bg-white p-6 rounded-xl shadow">
                <div>
                  <label for="cinema" class="font-semibold text-gray-700">🏢 Rạp</label>
                  <select name="cinema_id" id="cinema" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    onchange="loadRoomsForAdd(this.value)"> <option value="">-- Chọn rạp --</option>
                    <?php foreach ($cinemas as $c): ?>
                      <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div>
                  <label for="room" class="font-semibold text-gray-700">🏠 Phòng</label>
                  <select name="room_id" id="room" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    onchange="loadMoviesForAdd(this.value)"> <option value="">-- Chọn phòng --</option>
                  </select>
                </div>
                
                <div>
                  <label for="movie" class="font-semibold text-gray-700">🎞️ Phim</label>
                  <select name="movie_id" id="movie" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- Chọn phim --</option>
                    
                  </select>
                </div>

                <div>
                  <label for="date" class="font-semibold text-gray-700">📅 Ngày chiếu</label>
                  <input type="date" name="date" id="date" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                  <label for="time" class="font-semibold text-gray-700">⏰ Giờ chiếu</label>
                  <input type="time" name="time" id="time" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div class="md:col-span-2 lg:col-span-3 flex justify-end">
                  <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 shadow">
                    <i class="fa-solid fa-plus"></i> Thêm suất chiếu
                  </button>
                </div>
              </form>



              </div>
          <div class="bg-white rounded-2xl shadow-md p-6 animate-zoom-in">
          


            <main class="p-4 w-full">
              <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
                <div>
                  <label for="cinema" class="font-semibold text-gray-700 mr-3 block mb-1">Chọn rạp:</label>
                  <select name="cinema_id" id="cinema" class="px-4 py-2 border rounded-lg" onchange="this.form.submit()">
                    <option value="">-- Chọn rạp --</option>
                    <?php foreach ($cinemas as $c): ?>
                      <option value="<?= $c['id'] ?>" <?= $c['id'] == $selectedCinema ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="room" class="font-semibold text-gray-700 mr-3 block mb-1">Chọn phòng:</label>
                  <select name="room_id" id="room" class="px-4 py-2 border rounded-lg" onchange="this.form.submit()">
                    <option value="">-- Chọn phòng --</option>
                    <?php foreach ($rooms as $r): ?>
                      <option value="<?= $r['id'] ?>" <?= $r['id'] == $selectedRoom ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="movie" class="font-semibold text-gray-700 mr-3 block mb-1">Chọn phim:</label>
                  <select name="movie_id" id="movie" class="px-4 py-2 border rounded-lg" onchange="this.form.submit()">
                    <option value="">-- Tất cả phim trong phòng --</option>
                    <?php foreach ($movies as $m): ?>
                      <option value="<?= $m['id'] ?>" <?= $m['id'] == $selectedMovie ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['title']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

              </form>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if ($selectedRoom && $showtimes): ?>
                  <?php foreach ($showtimes as $s): ?>
                    <div class="bg-white shadow-md rounded-xl p-5 border hover:shadow-lg transition">
                      <div class="flex justify-between items-center mb-3">
                        <h2 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($s['movie_title']) ?></h2>
                        <span class="text-sm text-gray-500">#<?= $s['id'] ?></span>
                      </div>
                      <div class="space-y-1 text-gray-600">
                        <p><span class="font-semibold text-slate-700">🎬 Rạp:</span> <?= htmlspecialchars($s['cinema_name']) ?></p>
                        <p><span class="font-semibold text-slate-700">🏢 Phòng:</span> <?= htmlspecialchars($s['room_name']) ?></p>
                        <p><span class="font-semibold text-slate-700">📅 Ngày:</span> <?= $s['date'] ?></p>
                        <p><span class="font-semibold text-slate-700">⏰ Giờ:</span> <?= $s['time'] ?></p>
                      </div>
                      <div class="mt-4 flex justify-end gap-2">
                        <button type="button" class="px-2 py-1 hover:scale-110" 
                            onclick="openEditShowtimeModal(
                                <?= $s['id'] ?>, 
                                '<?= $s['date'] ?>', 
                                '<?= $s['time'] ?>', 
                                <?= $s['movie_id'] ?>,
                                '<?= addslashes($s['movie_title']) ?>'
                            )">
                            <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                        </button>

                        <button type="button" class="px-2 py-1 hover:scale-110" 
                            onclick="openDeleteShowtimeModal(<?= $s['id'] ?>)">
                            <i class="fa-solid fa-trash text-red-500"></i>
                        </button>
                    </div>
                    </div>
                  <?php endforeach; ?>
                <?php elseif ($selectedRoom): ?>
                  <p class="text-gray-600">❌ Không có suất chiếu nào cho lựa chọn này.</p>
                <?php else: ?>
                  <p class="text-gray-600">🎥 Vui lòng chọn rạp và phòng để xem suất chiếu.</p>
                <?php endif; ?>
              </div>
            </main>

          </div>
          
      </div>
      <div id="revenue" class="section hidden">
        
        
          
            <div class="overflow-x-auto rounded-xl">
              <?php
                include 'config/db.php';
                $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
                $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
                $prev_month = $month - 1;
                $prev_year = $year;
                if ($prev_month == 0) {
                  $prev_month = 12;
                  $prev_year--;
                }
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

                function percent_change($current, $previous) {
                  if ($previous == 0) return 100;
                  return round((($current - $previous) / $previous) * 100, 1);
                }

                $revenue_change = percent_change($total, $prev_total);
                $tickets_change = percent_change($tickets, $prev_tickets);


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
                <header class="  sticky top-0 z-10 animate-slide-up">
                  <div class="max-w-7xl mx-auto px-6 py-2 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-indigo-600">📊 Báo cáo doanh thu</h1>
                    <span class="text-sm text-gray-500">Cập nhật: <?php echo date("d/m/Y"); ?></span>
                  </div>
                </header>

                <main class="flex-1 max-w-7xl mx-auto px-6 py-4 animate-slide-up">

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

                  <div class="grid md:grid-cols-3 gap-6 mb-10">
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
                    <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-pink-500">
                      <h2 class="text-gray-500 text-sm mb-2 uppercase">Số ngày có doanh thu</h2>
                      <p class="text-3xl font-semibold text-gray-900"><?= count($days); ?></p>
                    </div>
                  </div>

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
            </div>
          
      </div>
      <div id="reports" class="section">
                <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 animate-slide-up">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">📊 Báo cáo & Thống kê</h3>
                        <p class="text-sm text-gray-500">Tổng hợp hiệu suất kinh doanh rạp chiếu phim</p>
                    </div>
                    <button onclick="exportReportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow flex items-center transition">
                        <i class="fa-solid fa-file-excel mr-2"></i> Xuất Báo Cáo Excel
                    </button>
                </div>

                <?php
                $res1 = $conn->query("SELECT SUM(total_amount) AS revenue, SUM(ticket_quantity) AS tickets FROM booking_history");
                $row1 = $res1->fetch_assoc();
                $totalRevenue = $row1['revenue'] ?? 0;
                $totalTickets = $row1['tickets'] ?? 0;

                $totalMovies = $conn->query("SELECT COUNT(*) AS total FROM movies")->fetch_assoc()['total'] ?? 0;
                $totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
                $totalGenres = $conn->query("SELECT COUNT(*) AS total FROM genre")->fetch_assoc()['total'] ?? 0;
                $sqlTopMovies = "SELECT movie_title, SUM(total_amount) as total FROM booking_history GROUP BY movie_title ORDER BY total DESC LIMIT 5";
                $resTopMovies = $conn->query($sqlTopMovies);
                $topMoviesLabels = [];
                $topMoviesData = [];
                while ($row = $resTopMovies->fetch_assoc()) {
                    $topMoviesLabels[] = $row['movie_title'];
                    $topMoviesData[] = $row['total'];
                }

                $sqlMonth = "SELECT DATE_FORMAT(booking_date, '%Y-%m') AS month, SUM(total_amount) AS revenue FROM booking_history GROUP BY month ORDER BY month ASC";
                $resMonth = $conn->query($sqlMonth);
                $months = [];
                $revenues = [];
                while ($row = $resMonth->fetch_assoc()) {
                    $months[] = $row['month'];
                    $revenues[] = $row['revenue'];
                }
                ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8 animate-slide-up">
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-slide-up">
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

                <div class="bg-white rounded-xl shadow p-6 mb-8 animate-slide-up">
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


                    function exportReportToExcel() {

                        var table = document.getElementById("reportTable");                   
                        var wb = XLSX.utils.table_to_book(table, {sheet: "BaoCaoChiTiet"});
                        XLSX.writeFile(wb, 'BaoCao_DoanhThu_' + new Date().toISOString().slice(0,10) + '.xlsx');
                    }
                </script>
            </div>
    </div>
  </div>
  
  <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Sửa Người Dùng</h3>
        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">✖</button>
      </div>
      <form id="editUserForm" action="suaND.php" method="POST" class="space-y-4">
        <input type="hidden" name="id" id="editUserId">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
            <input type="text" name="name" id="editUserName" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="editUserEmail" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input type="text" name="phone" id="editUserPhone" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
            <select name="status" id="editUserStatus" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500">
                <option value="1">Hoạt động</option>
                <option value="0">Khóa tài khoản</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Lưu thay đổi</button>
      </form>
    </div>
</div>

  

  <div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 text-center">
      <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-4xl"></i>
        </div>
      <h3 class="text-xl font-bold mb-4">Xác nhận xóa</h3>
      <p>Bạn có chắc muốn xóa người dùng này?</p>
      <form id="deleteUserForm" action="xoaND.php" method="POST" class="mt-4">
        <input type="hidden" name="id" id="deleteUserId">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Xóa</button>
        <button type="button" onclick="closeDeleteModal()" class="ml-2 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Hủy</button>
      </form>
    </div>
  </div>

  <div id="editStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Sửa Nhân viên</h3>
            <button onclick="closeEditModalStaff()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <form id="editStaffForm" action="suaNV.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="editStaffId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên nhân viên</label>
                <input type="text" name="name" id="editStaffName" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="editStaffEmail" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                <input type="text" name="phone" id="editStaffPhone" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                <div class="flex gap-2">
                    <select id="editRoleSelect" name="role" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 transition">
                      <?php foreach ($existing_roles as $r): ?>
                          <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                      <?php endforeach; ?>
                    </select>

                    <input type="text" id="editRoleInput" name="role" placeholder="Nhập vai trò mới..." disabled 
                           class="hidden w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 transition">

                    <button type="button" onclick="toggleRoleMode()" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 border border-gray-300 px-3 rounded-lg flex-shrink-0 transition"
                            title="Chuyển chế độ nhập">
                        <i class="fa-solid fa-plus" id="editRoleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg shadow transition">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

  <div id="deleteStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 text-center">
    <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-4xl"></i>
        </div>
      <h3 class="text-xl font-bold mb-4">Xác nhận xóa</h3>
      <p>Bạn có chắc muốn xóa nhân viên này?</p>
      <form id="deleteStaffForm" action="xoaNV.php" method="POST" class="mt-4">
        <input type="hidden" name="id" id="deleteStaffId">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Xóa</button>
        <button type="button" onclick="closeDeleteModalStaff()" class="ml-2 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Hủy</button>
      </form>
    </div>
  </div>

  <div id="editMoviesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Sửa Phim</h3>
        <button onclick="closeEditModalMovies()" class="text-gray-500 hover:text-gray-700">
          ✖
        </button>
      </div>
      <form id="editMoviesForm" action="suaMovies.php" method="POST" class="space-y-4" enctype="multipart/form-data">
        <input type="hidden" name="id" id="editMoviesId">
        <input type="hidden" name="old_poster" value="<?= htmlspecialchars($movie['poster']) ?>">
        <input type="text" name="title" id="editMoviesTitle" class="w-full border rounded p-2"  >
        <input type="text" name="genre" id="editMoviesGenre" class="w-full border rounded p-2"  >
        <input type="number" name="duration" id="editMoviesDuration" class="w-full border rounded p-2"  >
        <input type="number" step="0.01" name="price" id="editMoviesPrice" class="w-full border rounded p-2" >
        <textarea name="description" id="editMoviesDescription" class="w-full border rounded p-2"  rows="4" ></textarea>
        <label>Poster mới (nếu muốn thay):</label>
        <input type="file" name="poster" accept="image/*">
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Lưu</button>
    </form>

      
    </div>
  </div>
  <div id="deleteMoviesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-4xl"></i>
        </div>
          <h3 class="text-xl font-bold mb-4">Xác nhận xóa</h3>
          <p>Bạn có chắc muốn xóa phim này?</p>
          <form id="deleteMoviesForm" action="xoaMovies.php" method="POST" class="mt-4">
            <input type="hidden" name="id" id="deleteMoviesId">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Xóa</button>
            <button type="button" onclick="closeDeleteModalMovies()" class="ml-2 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Hủy</button>
          </form>
        </div>
      </div>
  <div id="editShowtimeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Sửa Suất Chiếu</h3>
            <button onclick="closeEditShowtimeModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        
        <form action="edit_showtime.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="edit_showtime_id">
            
            <input type="hidden" name="room_id" value="<?= $selectedRoom ?>"> 

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phim</label>
              <input type="hidden" name="movie_id" id="edit_movie_id">
              
              <input type="text" id="display_movie_name" readonly 
                    class="w-full border border-gray-300 bg-gray-100 text-gray-500 rounded-lg p-2 cursor-not-allowed">
          </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày chiếu</label>
                <input type="date" name="date" id="edit_showtime_date" required class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ chiếu</label>
                <input type="time" name="time" id="edit_showtime_time" required class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteShowtimeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Xác nhận xóa</h3>
        <p class="text-gray-600 mb-6">Bạn có chắc muốn xóa suất chiếu này? Hành động này không thể hoàn tác.</p>
        
        <form action="delete_showtime.php" method="POST" class="flex justify-center gap-4">
            <input type="hidden" name="id" id="delete_showtime_id">
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">Xóa Ngay</button>
            <button type="button" onclick="closeDeleteShowtimeModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">Hủy</button>
            
        </form>
    </div>
</div>
<div id="importUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Nhập Người Dùng</h3>
            <button onclick="closeImportModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        
        <form action="import_users.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-800 mb-4">
                <p class="font-bold"><i class="fa-solid fa-circle-info"></i> Lưu ý:</p>
                <ul class="list-disc list-inside mt-1">
                    <li>File phải có định dạng <strong>.csv</strong></li>
                    <li>Cấu trúc cột: <strong>Tên, Email, SĐT, Mật khẩu</strong></li>
                    <li>Email trùng sẽ bị bỏ qua.</li>
                </ul>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file CSV</label>
                <input type="file" name="csv_file" accept=".csv" required 
                       class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" name="import" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                    Tiến hành Nhập
                </button>
            </div>
        </form>
    </div>
</div>
<div id="importStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Nhập Nhân Viên</h3>
            <button onclick="closeImportStaffModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        
        <form action="import_staff.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-800 mb-4">
                <p class="font-bold"><i class="fa-solid fa-circle-info"></i> Lưu ý file Excel/CSV:</p>
                <ul class="list-disc list-inside mt-1">
                    <li>Cột A: <strong>Tên nhân viên</strong></li>
                    <li>Cột B: <strong>Email</strong> (Không được trùng)</li>
                    <li>Cột C: <strong>Số điện thoại</strong></li>
                    <li>Cột D: <strong>Vai trò</strong> (VD: Quản lý, Nhân viên)</li>
                    <li>Cột E: <strong>Mật khẩu</strong> (Nếu trống sẽ là 123456)</li>
                </ul>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file CSV</label>
                <input type="file" name="csv_file" accept=".csv" required 
                       class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" name="import_staff" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                    Tiến hành Nhập
                </button>
            </div>
        </form>
    </div>
</div>
<div id="addCinemaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Thêm Rạp Mới</h3>
        <form action="cinema_actions.php" method="POST" class="space-y-3">
            <input type="hidden" name="action" value="add_cinema">
            <input type="text" name="name" placeholder="Tên rạp (VD: CGV Vincom)" required class="w-full border p-2 rounded">
            <input type="text" name="address" placeholder="Địa chỉ" required class="w-full border p-2 rounded">
            <input type="text" name="phone" placeholder="Số điện thoại" required class="w-full border p-2 rounded">
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="this.closest('.fixed').classList.add('hidden');this.closest('.fixed').classList.remove('flex')" class="bg-gray-200 px-3 py-1 rounded">Hủy</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Thêm</button>
            </div>
        </form>
    </div>
</div>

<div id="editCinemaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Sửa Thông Tin Rạp</h3>
        <form action="cinema_actions.php" method="POST" class="space-y-3">
            <input type="hidden" name="action" value="edit_cinema">
            <input type="hidden" name="id" id="edit_cinema_id">
            <input type="text" name="name" id="edit_cinema_name" required class="w-full border p-2 rounded">
            <input type="text" name="address" id="edit_cinema_address" required class="w-full border p-2 rounded">
            <input type="text" name="phone" id="edit_cinema_phone" required class="w-full border p-2 rounded">
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('editCinemaModal').classList.add('hidden');document.getElementById('editCinemaModal').classList.remove('flex')" class="bg-gray-200 px-3 py-1 rounded">Hủy</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Lưu</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteCinemaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-80 text-center">
        <h3 class="text-lg font-bold mb-2 text-red-600">Xóa Rạp?</h3>
        <p class="text-sm text-gray-600 mb-4">Cảnh báo: Các phòng chiếu thuộc rạp này cũng sẽ bị xóa!</p>
        <form action="cinema_actions.php" method="POST" class="flex justify-center gap-3">
            <input type="hidden" name="action" value="delete_cinema">
            <input type="hidden" name="id" id="delete_cinema_id">
            <button type="button" onclick="document.getElementById('deleteCinemaModal').classList.add('hidden');document.getElementById('deleteCinemaModal').classList.remove('flex')" class="bg-gray-200 px-3 py-1 rounded">Hủy</button>
            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
        </form>
    </div>
</div>

<div id="addRoomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Thêm Phòng Chiếu</h3>
        <form action="cinema_actions.php" method="POST" class="space-y-3">
            <input type="hidden" name="action" value="add_room">
            
            <label class="block text-sm font-medium">Chọn Rạp</label>
            <select name="cinema_id" class="w-full border p-2 rounded mb-2" required>
                <?php 
                // Re-query cinema list for dropdown
                $resCinemas->data_seek(0); 
                while($c = $resCinemas->fetch_assoc()): 
                ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label class="block text-sm font-medium">Tên Phòng</label>
            <input type="text" name="name" placeholder="VD: Phòng 1, IMAX..." required class="w-full border p-2 rounded">
            
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden');document.getElementById('addRoomModal').classList.remove('flex')" class="bg-gray-200 px-3 py-1 rounded">Hủy</button>
                <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded">Thêm</button>
            </div>
        </form>
    </div>
</div>

<div id="editRoomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Sửa Phòng Chiếu</h3>
        <form action="cinema_actions.php" method="POST" class="space-y-3">
          <input type="hidden" name="action" value="edit_room">
          <input type="hidden" name="id" id="edit_room_id">
          
          <label class="block text-sm font-medium text-gray-700">Thuộc Rạp</label>
          
          <input type="hidden" name="cinema_id" id="edit_room_cinema_id_hidden">
          
          <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fa-solid fa-building"></i>
              </span>
              <input type="text" id="edit_room_cinema_name_display" readonly 
                    class="w-full border border-gray-300 bg-gray-100 text-gray-500 rounded-lg p-2 pl-10 cursor-not-allowed focus:outline-none">
          </div>

          <label class="block text-sm font-medium text-gray-700 mt-3">Tên Phòng</label>
          <input type="text" name="name" id="edit_room_name" required 
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 transition">
          
          <div class="flex justify-end gap-2 pt-4">
              <button type="button" onclick="document.getElementById('editRoomModal').classList.add('hidden');document.getElementById('editRoomModal').classList.remove('flex')" 
                      class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-gray-700 font-medium">Hủy</button>
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow font-medium">Lưu Thay Đổi</button>
          </div>
      </form>
    </div>
</div>

<div id="deleteRoomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-80 text-center">
        <h3 class="text-lg font-bold mb-2 text-red-600">Xóa Phòng?</h3>
        <p class="text-sm text-gray-600 mb-4">Các suất chiếu trong phòng này sẽ bị ảnh hưởng!</p>
        <form action="cinema_actions.php" method="POST" class="flex justify-center gap-3">
            <input type="hidden" name="action" value="delete_room">
            <input type="hidden" name="id" id="delete_room_id">
            <button type="button" onclick="document.getElementById('deleteRoomModal').classList.add('hidden');document.getElementById('deleteRoomModal').classList.remove('flex')" class="bg-gray-200 px-3 py-1 rounded">Hủy</button>
            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
        </form>
    </div>
</div>
<script>
function openImportStaffModal() {
    const modal = document.getElementById('importStaffModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeImportStaffModal() {
    const modal = document.getElementById('importStaffModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
  <script src="common.js"></script>
  <script>
    const ctx1 = document.getElementById("mostLikedChart").getContext("2d");
    new Chart(ctx1, {
      type: "bar",
      data: {
        labels: ["Hành động", "Hài kịch", "Chính kịch", "Kinh dị", "Tình cảm", "Tiểu sử","Hoạt hình","Khoa học viễn tưởng","Tài liệu"],
        datasets: [
          {
            label: "Likes",
            data: [2200, 1900, 1600, 1200, 1100, 1050,1100,1200,1150],
            backgroundColor: "#00aaff"
          }
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    const ctx2 = document.getElementById("leastLikedChart").getContext("2d");
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Hành động", "Hài kịch", "Chính kịch", "Kinh dị", "Tình cảm", "Tiểu sử","Hoạt hình","Khoa học viễn tưởng","Tài liệu"],
        datasets: [
          {
            label: "Likes",
            data: [400, 380, 350, 330, 310, 290,300,320,310],
            backgroundColor: "#ff4c4c",
            borderColor: "#ff4c4c",
            fill: false,
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: "top" } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.add('hidden'));
      document.getElementById(id).classList.remove('hidden');

      document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
      event.target.classList.add('active');
      localStorage.setItem("activeSection", id);
    }
function openEditModal(id, name, email, phone, status) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserPhone').value = phone;

    document.getElementById('editUserStatus').value = status !== undefined ? status : 1;

    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
}

    function closeEditModal() {
      document.getElementById('editUserModal').classList.add('hidden');
      document.getElementById('editUserModal').classList.remove('flex');
    }

    function openEditModalStaff(id, name, email, phone, role) {
    document.getElementById('editStaffId').value = id;
    document.getElementById('editStaffName').value = name;
    document.getElementById('editStaffEmail').value = email;
    document.getElementById('editStaffPhone').value = phone;
    const select = document.getElementById('editRoleSelect');
    const input = document.getElementById('editRoleInput');
    const icon = document.getElementById('editRoleIcon');
    let roleExists = false;
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === role) {
            roleExists = true;
            break;
        }
    }

    if (roleExists) {
        select.value = role;
        
        select.classList.remove('hidden');
        select.disabled = false;
        
        input.classList.add('hidden');
        input.disabled = true;
        
        icon.className = 'fa-solid fa-plus';
    } else {
        input.value = role;
        
        select.classList.add('hidden');
        select.disabled = true;
        
        input.classList.remove('hidden');
        input.disabled = false;
        
        icon.className = 'fa-solid fa-rotate-left';
    }

    const modal = document.getElementById('editStaffModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
    function closeEditModalStaff() {
      document.getElementById('editStaffModal').classList.add('hidden');
      document.getElementById('editStaffModal').classList.remove('flex');
    }

    function openEditModalMovies(id, title, genre, duration, price, description) {
    document.getElementById('editMoviesId').value = id;
    document.getElementById('editMoviesTitle').value = title;
    document.getElementById('editMoviesGenre').value = genre;
    document.getElementById('editMoviesDuration').value = duration;
    document.getElementById('editMoviesPrice').value = price;
    document.getElementById('editMoviesDescription').value = description;


    document.getElementById('editMoviesModal').classList.remove('hidden');
    document.getElementById('editMoviesModal').classList.add('flex');
}

    function closeEditModalMovies() {
        document.getElementById('editMoviesModal').classList.add('hidden');
        document.getElementById('editMoviesModal').classList.remove('flex');
    }
    function openDeleteModalMovies(id) {
    document.getElementById('deleteMoviesId').value = id;

    document.getElementById('deleteMoviesModal').classList.remove('hidden');
    document.getElementById('deleteMoviesModal').classList.add('flex');
    }

    function openDeleteModal(id) {
      document.getElementById('deleteUserId').value = id;
      document.getElementById('deleteUserModal').classList.remove('hidden');
      document.getElementById('deleteUserModal').classList.add('flex');
    }

    function closeDeleteModal() {
      document.getElementById('deleteUserModal').classList.add('hidden');
      document.getElementById('deleteUserModal').classList.remove('flex');
    }
    function openDeleteModalStaff(id) {
      document.getElementById('deleteStaffId').value = id;
      document.getElementById('deleteStaffModal').classList.remove('hidden');
      document.getElementById('deleteStaffModal').classList.add('flex');
    }
    function closeDeleteModalStaff() {
      document.getElementById('deleteStaffModal').classList.add('hidden');
      document.getElementById('deleteStaffModal').classList.remove('flex');
    }
    function closeDeleteModalMovies() {
      document.getElementById('deleteMoviesModal').classList.add('hidden');
      document.getElementById('deleteMoviesModal').classList.remove('flex');
    }
  </script>
  <script>
        window.addEventListener("DOMContentLoaded", () => {
      const lastSection = localStorage.getItem("activeSection") || "dashboard";
      showSection(lastSection);

      document.querySelectorAll('.sidebar a').forEach(link => {
        if (link.getAttribute('onclick').includes(lastSection)) {
          link.classList.add('active');
        }
      });
    });

  </script>
    <script>
      function openDetailModal(id, title, date, time, seats, quantity, price, total, bookingDate, code, type) {
        const content = `
          <p><i class="fa-solid fa-ticket text-blue-500 mr-2"></i><strong>ID vé:</strong> ${id}</p>
          <p><i class="fa-solid fa-film text-indigo-500 mr-2"></i><strong>Tên phim:</strong> ${title}</p>
          <p><i class="fa-solid fa-calendar-day text-green-500 mr-2"></i><strong>Ngày chiếu:</strong> ${date}</p>
          <p><i class="fa-solid fa-clock text-yellow-500 mr-2"></i><strong>Giờ chiếu:</strong> ${time}</p>
          <p><i class="fa-solid fa-chair text-purple-500 mr-2"></i><strong>Ghế:</strong> ${seats}</p>
          <p><i class="fa-solid fa-list-ol text-teal-500 mr-2"></i><strong>Số lượng vé:</strong> ${quantity}</p>
          <p><i class="fa-solid fa-coins text-amber-600 mr-2"></i><strong>Giá vé:</strong> ${price.toLocaleString()} VND</p>
          <p><i class="fa-solid fa-money-bill-wave text-emerald-600 mr-2"></i><strong>Tổng tiền:</strong> ${total.toLocaleString()} VND</p>
          <p><i class="fa-solid fa-calendar-check text-blue-600 mr-2"></i><strong>Ngày đặt:</strong> ${bookingDate}</p>
          <p><i class="fa-solid fa-barcode text-gray-600 mr-2"></i><strong>Mã đơn:</strong> ${code}</p>
          <p><i class="fa-solid fa-user text-pink-600 mr-2"></i><strong>Loại user:</strong> ${type}</p>
        `;
        document.getElementById('ticketDetailContent').innerHTML = content;
        document.getElementById('detailModal').classList.remove('hidden');
      }

      function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
      }
</script>
<script>
  document.getElementById('posterInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('posterPreview');

    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
      }
      reader.readAsDataURL(file);
    } else {
      preview.src = '#';
      preview.classList.add('hidden');
    }
  });
</script>
<script>
const checkboxes = document.querySelectorAll('.genre-checkbox');
const warning = document.getElementById('genre-warning');

checkboxes.forEach(cb => {
    cb.addEventListener('change', () => {
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        if (checked.length > 2) {
            cb.checked = false; 
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
        if (checked.length === 2) {
            checkboxes.forEach(box => {
                if (!box.checked) box.disabled = true;
            });
        } else {
            checkboxes.forEach(box => box.disabled = false);
        }
    });
});
</script>
<script>
function loadMovies(roomId) {
  const movieSelect = document.getElementById("movie");
  movieSelect.innerHTML = "<option>Đang tải...</option>";

  if (!roomId) {
    movieSelect.innerHTML = "<option value=''>-- Chọn phim --</option>";
    return;
  }

  fetch("get_movies.php?roomId=" + roomId)
    .then(response => response.text())
    .then(data => {
      movieSelect.innerHTML = data;
    });
}

</script>
<script>
function loadRoomsForAdd(cinemaId) {
        const roomSelect = document.getElementById("room");
        const movieSelect = document.getElementById("movie");

        // Reset trạng thái
        roomSelect.innerHTML = "<option value=''>Đang tải...</option>";
        movieSelect.innerHTML = "<option value=''>-- Chọn phim --</option>";

        if (!cinemaId) {
            roomSelect.innerHTML = "<option value=''>-- Chọn phòng --</option>";
            return;
        }

        fetch("get_rooms.php?cinemaId=" + cinemaId)
            .then(response => response.text())
            .then(data => {
                roomSelect.innerHTML = data;
            })
            .catch(err => console.error("Lỗi tải phòng:", err));
    }

    function loadMoviesForAdd(roomId) {
        const movieSelect = document.getElementById("movie");
        movieSelect.innerHTML = "<option value=''>Đang tải...</option>";

        if (!roomId) {
            movieSelect.innerHTML = "<option value=''>-- Chọn phim --</option>";
            return;
        }

        fetch("get_movies.php?roomId=" + roomId)
            .then(response => response.text())
            .then(data => {
                movieSelect.innerHTML = data;
            })
            .catch(err => console.error("Lỗi tải phim:", err));
    }
function loadRooms() {
    const cinemaId = document.getElementById("cinema-select").value;
    if (!cinemaId) return;

    fetch("get_rooms.php?cinemaId=" + cinemaId)
        .then(res => res.text())
        .then(data => {
            document.getElementById("room-select").innerHTML = data;
            document.getElementById("movie-select").innerHTML = "<option value=''>Chọn phim</option>";
            document.getElementById("date-select").innerHTML = "<option value=''>Chọn ngày chiếu</option>";
            document.getElementById("showtime-select").innerHTML = "<option value=''>Chọn suất chiếu</option>";
            document.getElementById("seat-map").innerHTML = "";
        });
}

function loadMovies() {
    const roomId = document.getElementById("room-select").value;
    if (!roomId) return;

    fetch("get_movies.php?roomId=" + roomId)
        .then(res => res.text())
        .then(data => {
            document.getElementById("movie-select").innerHTML = data;
            document.getElementById("date-select").innerHTML = "<option value=''>Chọn ngày chiếu</option>";
            document.getElementById("showtime-select").innerHTML = "<option value=''>Chọn suất chiếu</option>";
            document.getElementById("seat-map").innerHTML = "";
        });
}
</script>
<script>
  function loadDates() {
      const movieId = document.getElementById("movie-select").value;
      if (!movieId) return;

      fetch("get_dates.php?movieId=" + movieId)
          .then(res => res.text())
          .then(data => {
              document.getElementById("date-select").innerHTML = data;
              document.getElementById("showtime-select").innerHTML = "<option value=''>Chọn suất chiếu</option>";
              document.getElementById("seat-map").innerHTML = "";
          });
  }

  function loadShowtimes() {
      const movieId = document.getElementById("movie-select").value;
      const date = document.getElementById("date-select").value;
      if (!movieId || !date) return;

      fetch("get_showtimes.php?movieId=" + movieId + "&date=" + date)
          .then(res => res.text())
          .then(data => {
              document.getElementById("showtime-select").innerHTML = data;
              document.getElementById("seat-map").innerHTML = "";
          });
  }

  function loadSeats() {
    const movieId = document.getElementById("movie-select").value;
    const date = document.getElementById("date-select").value;
    const showtimeId = document.getElementById("showtime-select").value;

    document.getElementById("movie_id").value = movieId;
    document.getElementById("date").value = date;
    document.getElementById("showtimeId").value = showtimeId;

    if (!showtimeId) return;

    const selectedSeats = Array.from(document.querySelectorAll('#seat-map input[type="checkbox"]:checked'))
        .map(cb => cb.value);

    fetch("get_seats.php?showtimeId=" + showtimeId)
        .then(res => res.text())
        .then(data => {
            document.getElementById("seat-map").innerHTML = data;

            selectedSeats.forEach(seatId => {
                const seatCheckbox = document.querySelector(`#seat-map input[value="${seatId}"]`);
                if (seatCheckbox && !seatCheckbox.disabled) {
                    seatCheckbox.checked = true;
                    seatCheckbox.nextElementSibling.classList.add('selected');
                }
            });

            attachSeatClickEvents();
        });
}

</script>
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
  

  function attachSeatClickEvents() {
    document.querySelectorAll('#seat-map input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const seatId = this.value;
            const showtimeId = document.getElementById("showtime-select").value;

            if (!showtimeId) {
                alert('Vui lòng chọn suất chiếu trước!');
                this.checked = false;
                return;
            }

            if (this.checked) {
                fetch('seat_broken.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `seat_id=${encodeURIComponent(seatId)}&showtime_id=${encodeURIComponent(showtimeId)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.nextElementSibling.classList.add('selected');
                        console.log('Ghế giữ thành công:', seatId+showtimeId);
                    } else {
                        alert(data.error || 'Giữ ghế thất bại');
                        this.checked = false;
                    }
                });
            } else {
                fetch('seats_normal.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `seat_id=${encodeURIComponent(seatId)}&showtime_id=${encodeURIComponent(showtimeId)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.nextElementSibling.classList.remove('selected');
                        this.nextElementSibling.className = 'seat available bg-gray-200 cursor-pointer w-8 h-8 inline-flex items-center justify-center text-white font-semibold rounded';
                        console.log('Ghế hủy thành công:', seatId);
                    } else {
                        alert(data.error || 'Hủy ghế thất bại');
                        this.checked = true;
                    }
                });
            }
        });
    });
}
function openEditShowtimeModal(id, date, time, movieId, movieName) {
    document.getElementById('edit_showtime_id').value = id;
    document.getElementById('edit_showtime_date').value = date;
    
    const timeFormatted = time.length > 5 ? time.substring(0, 5) : time;
    document.getElementById('edit_showtime_time').value = timeFormatted;

    document.getElementById('edit_movie_id').value = movieId;

    document.getElementById('display_movie_name').value = movieName;

    const modal = document.getElementById('editShowtimeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditShowtimeModal() {
    const modal = document.getElementById('editShowtimeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openDeleteShowtimeModal(id) {
    document.getElementById('delete_showtime_id').value = id;
    const modal = document.getElementById('deleteShowtimeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteShowtimeModal() {
    const modal = document.getElementById('deleteShowtimeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function openImportModal() {
    const modal = document.getElementById('importUserModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImportModal() {
    const modal = document.getElementById('importUserModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
  </script>
  <script>
    function toggleRoleMode() {
        const select = document.getElementById('editRoleSelect');
        const input = document.getElementById('editRoleInput');
        const icon = document.getElementById('editRoleIcon');

    if (select.classList.contains('hidden')) {
        select.classList.remove('hidden');
        select.disabled = false; 
        
        input.classList.add('hidden');
        input.disabled = true; 
        
        icon.className = 'fa-solid fa-plus';
    } else {
        select.classList.add('hidden');
        select.disabled = true; 
        
        input.classList.remove('hidden');
        input.disabled = false; 
        input.focus();
        
        icon.className = 'fa-solid fa-rotate-left';
    }
    }
    function toggleRoleModeAdd() {
        const select = document.getElementById('roleSelect');
        const input = document.getElementById('roleInput');
        const icon = document.getElementById('roleIcon');

    if (select.classList.contains('hidden')) {

        select.classList.remove('hidden');
        select.disabled = false; 
        
        input.classList.add('hidden');
        input.disabled = true; 
        
        icon.className = 'fa-solid fa-plus';
    } else {

        select.classList.add('hidden');
        select.disabled = true; 
        
        input.classList.remove('hidden');
        input.disabled = false; 
        input.focus();
        
        icon.className = 'fa-solid fa-rotate-left';
    }
    }
</script>
<script>
function openEditCinemaModal(id, name, address, phone) {
    document.getElementById('edit_cinema_id').value = id;
    document.getElementById('edit_cinema_name').value = name;
    document.getElementById('edit_cinema_address').value = address;
    document.getElementById('edit_cinema_phone').value = phone;
    
    const modal = document.getElementById('editCinemaModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openDeleteCinemaModal(id) {
    document.getElementById('delete_cinema_id').value = id;
    const modal = document.getElementById('deleteCinemaModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function openEditRoomModal(id, name, cinemaId, cinemaName) {

    document.getElementById('edit_room_id').value = id;
    document.getElementById('edit_room_name').value = name;
    

    document.getElementById('edit_room_cinema_id_hidden').value = cinemaId;

    document.getElementById('edit_room_cinema_name_display').value = cinemaName;

    const modal = document.getElementById('editRoomModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openDeleteRoomModal(id) {
    document.getElementById('delete_room_id').value = id;
    const modal = document.getElementById('deleteRoomModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function toggleMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
      } else {
        menu.classList.add('hidden');
      }
    }
</script>

</body>
</html>
