<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<?php
$conn = new mysqli('localhost', 'uloehxcu_cinema', 'Minhdau22@', 'uloehxcu_cinema');
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);
$conn->set_charset("utf8mb4");


$selectedShowtimeId = null;
$movieIdFromUrl = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cinemaIdFromUrl = isset($_GET['cinema_id']) ? intval($_GET['cinema_id']) : 0;
$roomIdFromUrl = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
if (isset($_GET['showtime_id'])) {
    $selectedShowtimeId = (int)$_GET['showtime_id'];
} elseif (isset($_POST['showtime_id'])) {
    $selectedShowtimeId = (int)$_POST['showtime_id'];
}

$selectedSeats = [];
$totalPrice = 0;
$movieTitle = '';
$ticketPrice = 0;
$time = '';
$date = '';
$movieId = 0;
$showtimeId = 0;
$hideBooking = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seats'])) {
    $movieId = intval($_POST['movie_id']);
    $date = $_POST['date'];
    $showtimeId = intval($_POST['showtimeId']);
    $selectedSeats = $_POST['seats'];

    $sqlMovie = "SELECT title, price FROM movies WHERE id = ?";
    $stmt = $conn->prepare($sqlMovie);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $resultMovie = $stmt->get_result();
    if ($row = $resultMovie->fetch_assoc()) {
        $movieTitle = $row['title'];
        $ticketPrice = $row['price'];
    }


    $sqlShowtime = "SELECT DATE(time) as show_date, TIME(time) as show_time 
                    FROM showtimes WHERE id = ?";
    $stmt = $conn->prepare($sqlShowtime);
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();
    $resultShowtime = $stmt->get_result();
    if ($rowShow = $resultShowtime->fetch_assoc()) {
        $time = $rowShow['show_time'];
    }

    $totalPrice = $ticketPrice * count($selectedSeats);
     $hideBooking = true;
}
?>
<?php
// Lấy tên rạp và phòng chiếu từ CSDL dựa vào showtimeId
require_once 'config/db.php';
$cinemaName = '';
$roomName = '';

if (!empty($showtimeId)) {
    $stmt = $conn->prepare("
        SELECT c.name AS cinema_name, r.name AS room_name
        FROM showtimes s
        JOIN rooms r ON s.room_id = r.id
        JOIN cinemas c ON r.cinema_id = c.id
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $cinemaName = $row['cinema_name'];
        $roomName = $row['room_name'];
    }

    $stmt->close();
}


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaBooking - Đặt Vé</title>
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
                class="nav-link px-3 py-2 rounded-md text-sm font-medium bg-white bg-opacity-20"
                >Đặt Vé</a
              >
              <a
                href="history.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
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
                <div class="px-5 flex items-center">
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-purple-700">Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                        <div class="text-sm font-medium leading-none text-gray-500 mt-1"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="profile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                    <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-100">Đăng xuất</a>
                </div>
            <?php elseif (isset($_SESSION['admin'])): ?>
                 <div class="px-5">
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
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <?php if (!$hideBooking): ?>
              <div class=" lg:col-span-2" id="boking">
                  <div class="bg-white rounded-xl shadow-md p-6 mb-6 scroll-animate-left">
                      <h3 class="text-xl font-bold mb-4">Chọn Phim & Ngày Chiếu</h3>
                      <div class="grid gap-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <select id="cinema-select" class="px-4 py-2 border rounded-lg w-full" onchange="loadRooms()">
                            <option value="">Chọn rạp</option>
                            <?php
                            require_once 'config/db.php';
                            $sql = "SELECT id, name FROM cinemas";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $selectedCinema = ($cinemaIdFromUrl == $row['id']) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selectedCinema>{$row['name']}</option>";
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
                              $sql = "SELECT id, title FROM movies";
                              $result = $conn->query($sql);
                              while ($row = $result->fetch_assoc()) {
                                  $selected = ($movieIdFromUrl == $row['id']) ? 'selected' : '';
                                  echo "<option value='{$row['id']}' $selected>" . htmlspecialchars($row['title']) . "</option>";
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

                  <div class="bg-white rounded-xl shadow-md p-6 scroll-animate-left">
                      <h3 class="text-xl font-bold mb-4">Chọn Ghế Ngồi</h3>
                      <div class="mb-4">
                          <div class="screen"></div>
                      </div>
                      <form id="seat-form" method="POST"  >
                        <input type="hidden" name="movie_id" id="movie_id" value="">
                        <input type="hidden" name="date" id="date" value="">
                        <input type="hidden" name="showtimeId" id="showtimeId" value="">

                        <div id="seat-map" class="flex flex-col items-center space-y-2"></div>

                        <button type="submit" class="mt-4 bg-purple-600 text-white px-4 py-2 hover:bg-purple-700 rounded">
                          Xác nhận ghế
                        </button>
                      </form>



                      <div class="flex flex-wrap justify-center gap-4 mt-8 pt-4 border-t border-gray-200 text-sm font-medium text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-gray-200 border border-gray-300 shadow-sm"></div>
                            <span>Trống</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-blue-500 border-2 border-blue-600 shadow-sm"></div>
                            <span>Đã chọn</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-yellow-400 border border-yellow-500 shadow-sm"></div>
                            <span>Đang giữ</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-red-500 border border-red-600 shadow-sm"></div>
                            <span>Đã đặt</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-gray-600 flex items-center justify-center text-white opacity-50 shadow-sm">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </div>
                            <span>Bảo trì</span>
                        </div>

                    </div>
                  </div>
              </div>
              <?php endif; ?>

              <div class="lg:col-span-1">
                  <div class="bg-white rounded-xl shadow-md p-6 sticky top-4 scroll-animate-right">
                      <h3 class="text-xl font-bold mb-4">Thông Tin Đặt Vé</h3>
                      <div id="booking-summary" class="space-y-3 mb-6">
                        <?php if (!empty($selectedSeats)): ?>
                            <?php if (!empty($cinemaName)): ?>
                                <div><strong>Rạp:</strong> <span><?= htmlspecialchars($cinemaName) ?></span></div>
                            <?php endif; ?>
                            <?php if (!empty($roomName)): ?>
                                <div><strong>Phòng chiếu:</strong> <span><?= htmlspecialchars($roomName) ?></span></div>
                            <?php endif; ?>
                            <div><strong>Phim:</strong> <?= htmlspecialchars($movieTitle) ?></div>
                            <div><strong>Ngày chiếu:</strong> <?= htmlspecialchars($date) ?></div>
                            <div><strong>Suất chiếu:</strong> <?= htmlspecialchars($time) ?></div>
                            <div><strong>Ghế đã chọn:</strong> <?= implode(", ", $selectedSeats) ?></div>
                            <div><strong>Số lượng:</strong> <?= count($selectedSeats) ?> vé</div>
                            <div><strong>Giá mỗi vé:</strong> <?= number_format($ticketPrice) ?> VNĐ</div>
                            
                            <hr class="border-gray-200 my-3">

                            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <h4 class="font-bold text-gray-700 mb-2 text-sm flex items-center">
                                    🎫 Mã Khuyến Mãi
                                </h4>
                                
                                <div id="available-vouchers" class="space-y-2 mb-3 max-h-32 overflow-y-auto custom-scrollbar">
                                    <p class="text-xs text-gray-400 italic">Đang tải ưu đãi...</p>
                                </div>

                                <div class="relative mb-2">
                                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                                    <div class="relative flex justify-center text-xs"><span class="px-2 bg-white text-gray-400">Hoặc nhập mã</span></div>
                                </div>

                                <div class="flex space-x-2">
                                    <input type="text" id="promo-code-input" placeholder="Nhập mã..." 
                                          class="flex-1 px-3 py-2 border rounded text-sm focus:ring-2 focus:ring-purple-500 uppercase">
                                    <button type="button" onclick="applyPromotion()" 
                                            class="bg-gray-800 text-white px-3 py-2 rounded text-sm hover:bg-gray-900">
                                        Áp dụng
                                    </button>
                                </div>
                                <p id="promo-message" class="text-xs mt-1 hidden"></p>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-gray-600">
                                <span>Tạm tính:</span>
                                <span><?= number_format($totalPrice) ?> VNĐ</span>
                            </div>
                            <div class="flex justify-between items-center text-green-600 font-medium" id="row-discount" style="display:none;">
                                <span>Giảm giá:</span>
                                <span id="display-discount">-0 VNĐ</span>
                            </div>
                            <div class="flex justify-between items-center text-xl font-bold text-purple-700 mt-2 border-t pt-2">
                                <span>Tổng thanh toán:</span>
                                <span id="display-total"><?= number_format($totalPrice) ?> VNĐ</span>
                            </div>

                        <?php else: ?>
                            <div class="text-gray-500">Chưa chọn ghế</div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($selectedSeats)): ?>
                        <form method="POST" action="payment.php" id="payment-form">
                            <input type="hidden" name="cinemaName" value="<?= htmlspecialchars($cinemaName) ?>">
                            <input type="hidden" name="roomName" value="<?= htmlspecialchars($roomName) ?>">
                            <input type="hidden" name="movieTitle" value="<?= htmlspecialchars($movieTitle) ?>">
                            <input type="hidden" name="moviePrice" value="<?= $ticketPrice ?>">
                            <input type="hidden" name="showtime" value="<?= htmlspecialchars($time ?? '') ?>">
                            <input type="hidden" name="date" value="<?= htmlspecialchars($date ?? '') ?>">
                            <input type="hidden" name="seats" value="<?= implode(",", $selectedSeats) ?>">
                            <input type="hidden" name="showtimeId" value="<?= (int)$showtimeId ?>">
                            <input type="hidden" name="movieId" value="<?= $movieId ?>">
                            
                            <input type="hidden" name="original_total" id="input-original-total" value="<?= $totalPrice ?>">
                            <input type="hidden" name="promotion_code" id="input-promotion-code" value="">
                            <input type="hidden" name="total" id="input-final-total" value="<?= $totalPrice ?>">

                            <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold mt-4 hover:bg-purple-700 transition-colors">
                                Tiến Hành Thanh Toán
                            </button>
                        </form>
                    <?php endif; ?>

                      
                  </div>
              </div>
          </div>
      <?php else: ?>
          <div class="bg-white rounded-xl shadow-md p-10 text-center">
              <h2 class="text-2xl font-bold mb-4">Bạn cần đăng nhập để đặt vé</h2>
              <p class="text-gray-600 mb-6">Hãy đăng nhập để chọn ghế và thanh toán nhanh chóng.</p>
              <button onclick="showLoginModal()" 
                  class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                  Đăng Nhập Ngay
              </button>
          </div>
      <?php endif; ?>
    </main>

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
                fetch('hold_seat.php', {
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
                fetch('release_seat.php', {
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









  
</script>
<script>
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
    if (!roomId) {

        document.getElementById("movie-select").innerHTML = "<option value=''>Chọn phim</option>";
        document.getElementById("date-select").innerHTML = "<option value=''>Chọn ngày chiếu</option>";
        document.getElementById("showtime-select").innerHTML = "<option value=''>Chọn suất chiếu</option>";
        return;
    }

    const currentMovieId = document.getElementById("movie-select").value;

    fetch("get_movies.php?roomId=" + roomId)
        .then(res => res.text())
        .then(data => {
            document.getElementById("movie-select").innerHTML = data;
            
            const movieOption = document.querySelector(`#movie-select option[value='${currentMovieId}']`);

            if (movieOption) {
                document.getElementById("movie-select").value = currentMovieId;
            
                loadDates();
            } else {

                document.getElementById("date-select").innerHTML = "<option value=''>Chọn ngày chiếu</option>";
                document.getElementById("showtime-select").innerHTML = "<option value=''>Chọn suất chiếu</option>";
                document.getElementById("seat-map").innerHTML = "";
            }
        });
}
document.addEventListener("DOMContentLoaded", function() {
     const cinemaSelect = document.getElementById("cinema-select");
     const movieSelect = document.getElementById("movie-select");
     const roomSelect = document.getElementById("room-select");


     const preselectedRoomId = "<?= $roomIdFromUrl ?? 0 ?>";

     if (cinemaSelect.value && movieSelect.value) {
         console.log("Phát hiện luồng chi tiết phim. Tự động tải phòng...");
         
         const cinemaId = cinemaSelect.value;

         fetch("get_rooms.php?cinemaId=" + cinemaId)
             .then(res => res.text())
             .then(data => {
                 roomSelect.innerHTML = data;
                 
                 if (preselectedRoomId !== "0") {
                     const roomOption = roomSelect.querySelector(`option[value='${preselectedRoomId}']`);
                     
                     if (roomOption) {
                         roomSelect.value = preselectedRoomId;
                         console.log("Đã tự động chọn phòng: " + preselectedRoomId);
                         
                         loadMovies(); 
                     } else {
                         console.warn("Room ID " + preselectedRoomId + " (từ URL) không hợp lệ cho rạp này. Người dùng phải chọn thủ công.");
                     }
                 } else {
                    console.log("Không có room_id từ URL, người dùng phải chọn phòng thủ công.");
                 }
             });
     }
 });

</script>
<script>
    let currentVoucher = null; 

    document.addEventListener("DOMContentLoaded", function() {
        const voucherContainer = document.getElementById('available-vouchers');
        if (voucherContainer) {
            loadAvailableVouchers();
        }
    });

    function loadAvailableVouchers() {
        const movieId = "<?= $movieId ?? 0 ?>"; 
        const container = document.getElementById('available-vouchers');
        
        if (!container) return;
        
        container.innerHTML = '<p class="text-xs text-gray-400 italic">Đang tải ưu đãi...</p>';

        fetch(`get_promotions.php?movie_id=${movieId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-xs text-gray-400 italic">Không có mã ưu đãi nào.</p>';
                    return;
                }

                let html = '';
                data.forEach(promo => {
                    let valueNum = parseFloat(promo.discount_value); 
                    
                    let discountText = '';
                    if (promo.discount_type === 'percent') {
                        discountText = `${valueNum}%`; 
                    } else {
                        discountText = `${new Intl.NumberFormat('vi-VN').format(valueNum)}đ`; 
                    }

                    let minOrderText = '';
                    if (promo.min_order_value > 0) {
                        let minVal = parseFloat(promo.min_order_value);
                        minOrderText = `<div class="text-[10px] text-gray-500 mt-1">Đơn tối thiểu: ${new Intl.NumberFormat('vi-VN').format(minVal)}đ</div>`;
                    }

                    html += `
                    <label class="flex items-start p-2 border rounded cursor-pointer hover:bg-purple-50 text-sm mb-2 border-gray-200 transition-colors">
                        <input type="radio" name="selected_voucher" value="${promo.code}" 
                               class="mt-1 mr-2 w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500"
                               onclick="selectVoucher('${promo.code}')">
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800">${promo.code}</span>
                                <span class="text-red-600 font-bold">Giảm ${discountText}</span>
                            </div>
                            ${minOrderText}
                        </div>
                    </label>`;
                });
                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Lỗi Javascript:', error);
                container.innerHTML = '<p class="text-xs text-red-400">Lỗi tải dữ liệu.</p>';
            });
    }

    function selectVoucher(code) {
        const radio = document.querySelector(`input[name="selected_voucher"][value="${code}"]`);
        const input = document.getElementById('promo-code-input');
        const originalTotal = document.getElementById('input-original-total').value;

        if (currentVoucher === code) {
            resetPrice(originalTotal); 
            
            input.value = ""; 
            input.readOnly = false; 
            
            if(radio) radio.checked = false; 

            const msg = document.getElementById('promo-message');
            msg.innerText = "";
            msg.classList.add('hidden');

            currentVoucher = null;
            return; 
        }

        currentVoucher = code;
        input.value = code;
        applyPromotion();
    }

    function applyPromotion() {
        const code = document.getElementById('promo-code-input').value.trim();
        const originalTotal = document.getElementById('input-original-total').value;
        const movieId = "<?= $movieId ?>";
        const msg = document.getElementById('promo-message');

        if (!code) return;
        
        msg.innerText = "Đang kiểm tra..."; 
        msg.classList.remove('hidden', 'text-red-600', 'text-green-600');
        msg.classList.add('text-gray-500');

        fetch('check_promotion.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `code=${encodeURIComponent(code)}&total_amount=${originalTotal}&movie_id=${movieId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msg.className = "text-xs mt-1 text-green-600 font-bold";
                msg.innerText = data.message;

                document.getElementById('row-discount').style.display = 'flex';
                document.getElementById('display-discount').innerText = '-' + new Intl.NumberFormat('vi-VN').format(data.discount_amount) + ' VNĐ';
                document.getElementById('display-total').innerText = new Intl.NumberFormat('vi-VN').format(data.new_total) + ' VNĐ';

                document.getElementById('input-final-total').value = data.new_total;
                document.getElementById('input-promotion-code').value = data.promotion_code;

                document.getElementById('promo-code-input').readOnly = true;

                currentVoucher = data.promotion_code;
            } else {
                msg.className = "text-xs mt-1 text-red-600";
                msg.innerText = data.message;
                
                resetPrice(originalTotal);
                currentVoucher = null;
            }
        });
    }

    function resetPrice(originalTotal) {
        document.getElementById('row-discount').style.display = 'none';
        
        const totalNum = parseFloat(originalTotal);
        document.getElementById('display-total').innerText = new Intl.NumberFormat('vi-VN').format(totalNum) + ' VNĐ';

        document.getElementById('input-final-total').value = originalTotal;
        document.getElementById('input-promotion-code').value = "";

        document.getElementById('promo-code-input').readOnly = false;

        const radios = document.querySelectorAll('input[name="selected_voucher"]');
        radios.forEach(r => r.checked = false);
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