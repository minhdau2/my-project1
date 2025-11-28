<?php
session_start();
require 'config/db.php';

$userId = null;
$userType = null;

if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    $userType = 'user';
} elseif (isset($_SESSION['admin']['id'])) {
    $userId = $_SESSION['admin']['id'];
    $userType = 'admin';
}

$bookings = [];
if ($userId) {
    $sql = "SELECT * FROM booking_history WHERE user_id = ? AND user_type = ? ORDER BY booking_date DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("is", $userId, $userType);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaBooking - Lịch Sử Đặt Vé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
    <nav class="gradient-bg text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold">🎬 CinemaBooking</h1>
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a
                href="index.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
                >Trang Chủ</a
              >
              <a
                href="movies.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
                >Phim</a
              >
              <a
                href="booking.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
                >Đặt Vé</a
              >
              <a
                href="history.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium bg-white bg-opacity-20"
                >Lịch Sử</a
              >
              <form action="search.php" method="GET" class="relative">
                <input
                  type="text"
                  name="q"
                  placeholder="Tìm kiếm phim..."
                  class="pl-10 pr-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-white"
                />
                <svg
                  class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </form>
            </div>
          </div>
          <div class="hidden md:flex items-center space-x-4">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="relative group">
                    <button class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium">
                        Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?> ▼
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                        <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                        <a href="history.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Lịch sử đặt vé</a>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php elseif (isset($_SESSION['admin'])): ?>
                <div class="relative group">
                    <button class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium">
                        Xin chào, Admin <?= htmlspecialchars($_SESSION['admin']['name']) ?> ▼
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Quản trị</a>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <button
                  onclick="showLoginModal()"
                  class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors"
                >
                  Đăng Nhập
                </button>
            <?php endif; ?>
          </div>
          <div class="-mr-2 flex md:hidden">
            <button onclick="toggleMobileMenu()" type="button" class="bg-purple-600 inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-purple-700 focus:outline-none">
              <span class="sr-only">Open main menu</span>
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div id="mobile-menu" class="hidden md:hidden bg-white text-gray-800 border-t shadow-xl">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium text-purple-600 bg-gray-100">Trang Chủ</a>
          <a href="movies.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Phim</a>
          <a href="booking.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Đặt Vé</a>
          <a href="history.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Lịch Sử</a>
          
          <div class="px-3 py-2">
              <input type="text" placeholder="Tìm kiếm phim..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
          </div>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="px-2 flex items-center">
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-purple-700">Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                        
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="profile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                    <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-100">Đăng xuất</a>
                </div>
            <?php elseif (isset($_SESSION['admin'])): ?>
                 <div class="px-2">
                    <div class="text-base font-medium leading-none text-purple-700">Admin <?= htmlspecialchars($_SESSION['admin']['name']) ?></div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Quản trị</a>
                    <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-100">Đăng xuất</a>
                </div>
            <?php else: ?>
                <div class="mt-3 px-5">
                    <button onclick="showLoginModal(); toggleMobileMenu();" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg font-medium">
                        Đăng Nhập
                    </button>
                </div>
            <?php endif; ?>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])): ?>
        <div id="booking-history" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
              <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative border border-gray-200">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 flex justify-between items-center">
                  <h3 class="text-lg font-bold"><?= htmlspecialchars($booking['movie_title']) ?></h3>
                  <span class="text-sm bg-white text-purple-600 px-3 py-1 rounded-full font-semibold"><?= htmlspecialchars($booking['order_code']) ?></span>
                </div>

                <div class="p-5 space-y-2 text-gray-700">
                  <p>🎬 <strong>Rạp:</strong> <?= htmlspecialchars($booking['cinema_name']?? '') ?></p>
                  <p>🏛️ <strong>Phòng chiếu:</strong> <?= htmlspecialchars($booking['room_name']?? '') ?></p>
                  <p>📅 <strong>Ngày:</strong> <?= htmlspecialchars($booking['show_date']) ?></p>
                  <p>⏰ <strong>Giờ:</strong> <?= htmlspecialchars($booking['show_time']) ?></p>
                  <p>🎟️ <strong>Ghế:</strong> <?= htmlspecialchars($booking['seats']) ?></p>
                  <p>👥 <strong>Số lượng:</strong> <?= htmlspecialchars($booking['ticket_quantity']) ?></p>
                  <p>💵 <strong>Giá vé:</strong> <?= number_format($booking['ticket_price']) ?> VNĐ</p>
                  <p>🧾 <strong>Tổng cộng:</strong> <span class="text-purple-600 font-bold"><?= number_format($booking['total_amount']) ?> VNĐ</span></p>
                </div>

                <div class="bg-gray-100 px-5 py-3 flex justify-between items-center border-t">
                  <span class="text-xs text-gray-500">Giờ đặt: <?= date('H:i ', strtotime($booking['booking_date'])) ?></span>
                  <span class="text-xs text-gray-500">Ngày đặt: <?= date(' d/m/Y', strtotime($booking['booking_date'])) ?></span>
                  <a href="export_ticket.php?id=<?= $booking['id'] ?>" target="_blank"
                     class="bg-purple-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    <i class="fa-solid fa-print"></i> Xuất vé
                  </a>
                </div>
                
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-span-full text-center py-12">
              <div class="text-6xl mb-4">🎫</div>
              <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có lịch sử đặt vé</h3>
              <p class="text-gray-500 mb-6">Hãy đặt vé xem phim đầu tiên của bạn!</p>
              <a href="movies.php" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                Xem Phim Ngay
              </a>
            </div>
          <?php endif; ?>
        </div>

      <?php else: ?>
          <div class="bg-white rounded-xl shadow-md p-10 text-center">
              <h2 class="text-2xl font-bold mb-4">Bạn cần đăng nhập để xem lịch sử đặt vé</h2>
              <p class="text-gray-600 mb-6">Vui lòng đăng nhập để xem lại các vé đã đặt và quản lý thông tin tài khoản của bạn.</p>
              <button onclick="showLoginModal()" 
                  class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                  Đăng Nhập Ngay
              </button>
          </div>
      <?php endif; ?>
    </main>
    <div
      id="login-modal"
      class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold">Đăng Nhập</h3>
          <button
            onclick="hideLoginModal()"
            class="text-gray-500 hover:text-gray-700"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
        <form action="login.php" method="POST" class="space-y-4">
          <input
            type="email"
            name="email"
            placeholder="Email"
            class="w-full px-4 py-3 border rounded-lg"
            required
          />
          <input
            type="password"
            name="password"
            placeholder="Mật khẩu"
            class="w-full px-4 py-3 border rounded-lg"
            required
          />
          <button
            type="submit"
            class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors"
          >
            Đăng Nhập
          </button>
        </form>
        <?php if (isset($_SESSION['error'])): ?>
          <div class="bg-red-100 text-red-600 px-4 py-2 rounded mt-3 text-center">
            <?= htmlspecialchars($_SESSION['error']) ?>
          </div>
        <?php endif; ?>

        <div class="text-center mt-4">
          <a
            href="#"
            onclick="openRegister()"
            class="text-purple-600 hover:underline"
            >Chưa có tài khoản? Đăng ký ngay</a
          >
        </div>
      </div>
    </div>

    <div
      id="Register-modal"
      class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold">Đăng Ký</h3>
          <button
            onclick="hideRegisterModal()"
            class="text-gray-500 hover:text-gray-700"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
        <form action="register.php" method="POST" class="space-y-4">
          <input type="text" name="name" placeholder="Họ và tên" required class="w-full px-4 py-3 border rounded-lg" />
          <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-3 border rounded-lg" />
          <input type="text" name="phone" placeholder="Số điện thoại" required class="w-full px-4 py-3 border rounded-lg" />
          <input type="password" name="password" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required class="w-full px-4 py-3 border rounded-lg" />
          <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required class="w-full px-4 py-3 border rounded-lg" />
          <input type="checkbox" id="agreeTerms" required class="mr-2" />
          <span class="text-sm text-gray-700">
            Tôi đồng ý với
            <a href="#" class="text-purple-600 hover:underline">Điều khoản sử dụng</a>
            và
            <a href="#" class="text-purple-600 hover:underline">Chính sách bảo mật</a>
          </span>
          <button
            type="submit"
            class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors"
          >
            Đăng ký
          </button>
        </form>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="bg-red-100 text-red-600 px-4 py-2 rounded mt-3 text-center">
            <?= htmlspecialchars($_SESSION['error']) ?>
          </div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="bg-green-100 text-green-600 px-4 py-2 rounded mt-3 text-center">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="text-center mt-4">
          <a
            href="#"
            onclick="openLogin()"
            class="text-purple-600 hover:underline"
            >Đã có tài khoản? Đăng nhập ngay</a
          >
        </div>
      </div>
    </div>
    <script src="common.js"></script>
    
    <?php if (isset($_SESSION['error'])): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          let modal = document.getElementById("login-modal");
          if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
          }
        });
      </script>
  <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['show_register']) && $_SESSION['show_register']): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById("Register-modal");
      if (modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
      }
    });
  </script>
  <?php unset($_SESSION['show_register']); ?>
  <?php endif; ?>
<script>
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