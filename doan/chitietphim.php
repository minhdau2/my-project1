<?php
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID phim!");
}
$movie_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT 
        m.id, m.title, m.genre, m.duration, m.price, m.description, m.poster, m.status, m.trailer_url,
        r.cinema_id,
        s.room_id
    FROM movies m
    LEFT JOIN showtimes s ON s.movie_id = m.id
    LEFT JOIN rooms r ON s.room_id = r.id
    WHERE m.id = ?
    ORDER BY s.id ASC
    LIMIT 1
");

if (!$stmt) {
    die("Lỗi truy vấn phim: " . $conn->error);
}
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    die("Không tìm thấy phim!");
}

$canRate = false;
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;

if ($user_id > 0) {
    $checkSql = "SELECT id FROM booking_history WHERE user_id = ? AND movie_id = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    if ($checkStmt) {
        $checkStmt->bind_param("ii", $user_id, $movie_id);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            $canRate = true;
        }
        $checkStmt->close();
    }
}

$reviews = [];
$sqlReview = "SELECT r.*, u.name as user_name 
              FROM ratings r 
              JOIN users u ON r.user_id = u.id 
              WHERE r.movie_id = ? 
              ORDER BY r.created_at DESC";

if ($stmtRev = $conn->prepare($sqlReview)) {
    $stmtRev->bind_param("i", $movie_id);
    $stmtRev->execute();
    $reviews = $stmtRev->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?> - Chi tiết phim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css" />
    <style>

        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #facc15; 
        }
    </style>
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
              <a href="index.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium bg-white bg-opacity-20">Trang Chủ</a>
              <a href="movies.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Phim</a>
              <a href="booking.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Đặt Vé</a>
              <a href="history.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Lịch Sử</a>
              <div class="relative">
                <input type="text" id="searchInput" name="q" placeholder="Tìm kiếm phim..." class="pl-10 pr-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
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
                <button onclick="showLoginModal()" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
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

<main class="max-w-7xl mx-auto mt-10 px-6 pb-20">
    
    <section class="bg-white rounded-2xl shadow-md p-8 mb-8 scroll-animate">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>"
                     class="rounded-xl shadow-lg w-full object-cover h-auto">
            </div>
            <div class="md:col-span-2 space-y-4">
                <h2 class="text-4xl font-bold text-indigo-700"><?= htmlspecialchars($movie['title']) ?></h2>
                <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($movie['description']) ?></p>

                <div class="grid grid-cols-2 gap-4 mt-4 text-gray-700">
                    <p><span class="font-semibold text-indigo-700">🎭 Thể loại:</span> <?= htmlspecialchars($movie['genre']) ?></p>
                    <p><span class="font-semibold text-indigo-700">⏱️ Thời lượng:</span> <?= htmlspecialchars($movie['duration']) ?> phút</p>
                    <p><span class="font-semibold text-indigo-700">💰 Giá vé:</span> <?= number_format($movie['price'], 0, ',', '.') ?> VNĐ</p>
                    <p><span class="font-semibold text-indigo-700">📺 Trạng thái:</span> <?= htmlspecialchars($movie['status']) ?></p>
                </div>

                <div class="mt-6">
                    <a href="booking.php?id=<?= $movie_id ?>&cinema_id=<?= $movie['cinema_id'] ?? 0 ?>&room_id=<?= $movie['room_id'] ?? 0 ?>" 
                       class="bg-purple-600 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-purple-700 transition text-white inline-flex items-center">
                       <i class="fa-solid fa-ticket mr-2"></i> Đặt vé ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white rounded-2xl shadow-md p-8 mb-8 scroll-animate">
        <h3 class="text-2xl font-semibold mb-4 text-gray-700">🎞 Trailer phim</h3>
        <?php if (!empty($movie['trailer_url'])): ?>
            <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden relative h-96">
                <iframe class="w-full h-full rounded-xl absolute top-0 left-0" 
                        src="<?= htmlspecialchars($movie['trailer_url']) ?>" 
                        title="YouTube trailer"
                        allowfullscreen></iframe>
            </div>
        <?php else: ?>
            <p class="text-gray-500 italic">Phim này hiện chưa có trailer.</p>
        <?php endif; ?>
    </section>

    <section class="bg-white rounded-2xl shadow-md p-8 mb-8 scroll-animate">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-gray-700 flex items-center">
                ⭐ Đánh giá từ khán giả
                <span class="ml-3 text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold">
                    <?= count($reviews) ?> đánh giá
                </span>
            </h3>
        </div>

        <?php if ($canRate): ?>
            <div class="mb-10 bg-indigo-50 p-6 rounded-xl border border-indigo-100 shadow-sm">
                <h4 class="font-bold text-indigo-800 mb-3 text-lg">Bạn đã xem phim này, hãy để lại đánh giá nhé!</h4>
                
                <form action="submit_rating.php" method="POST">
                    <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
                    
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-gray-700 font-medium">Chấm điểm:</span>
                        <div class="rating-stars flex flex-row-reverse justify-end group">
                            <?php for($i=5; $i>=1; $i--): ?>
                                <input type="radio" id="star<?= $i ?>" name="stars" value="<?= $i ?>" class="hidden peer" required />
                                <label for="star<?= $i ?>" class="cursor-pointer text-gray-300 text-3xl transition-colors px-1">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <textarea name="comment" rows="3" required
                            placeholder="Chia sẻ cảm nghĩ chân thực của bạn về bộ phim..."
                            class="w-full p-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-gray-700"></textarea>
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium transition shadow-md flex items-center">
                        <i class="fa-regular fa-paper-plane mr-2"></i> Gửi đánh giá
                    </button>
                </form>
            </div>

        <?php elseif ($user_id > 0): ?>
            <div class="mb-10 p-6 bg-gray-100 rounded-xl text-gray-600 text-center italic border border-gray-200">
                <i class="fa-solid fa-ticket text-2xl mb-2 text-gray-400 block"></i>
                Bạn cần <strong>mua vé và xem phim này</strong> mới có thể viết đánh giá.
            </div>

        <?php else: ?>
            <div class="mb-10 p-6 bg-gray-100 rounded-xl text-center border border-gray-200">
                <p class="text-gray-600">
                    Vui lòng <button onclick="showLoginModal()" class="text-indigo-600 font-bold hover:underline focus:outline-none">Đăng nhập</button> để xem và viết đánh giá.
                </p>
            </div>
        <?php endif; ?>

        <div class="space-y-6">
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $rv): ?>
                    <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                    <?= strtoupper(substr($rv['user_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">
                                        <?= htmlspecialchars($rv['user_name'] ?? 'Người dùng ẩn danh') ?>
                                    </p>
                                    <div class="flex text-yellow-400 text-xs mt-0.5">
                                        <?php for($k=1; $k<=5; $k++): ?>
                                            <i class="fa-<?= $k <= $rv['stars'] ? 'solid' : 'regular' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded">
                                <?= date("d/m/Y H:i", strtotime($rv['created_at'])) ?>
                            </span>
                        </div>
                        <div class="pl-14">
                            <p class="text-gray-600 leading-relaxed text-sm">
                                <?= nl2br(htmlspecialchars($rv['comment'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-12 text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <i class="fa-regular fa-comment-dots text-4xl mb-3 opacity-50"></i>
                    <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                </div>
            <?php endif; ?>
        </div>

    </section>
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
          <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Hoặc tiếp tục với</span>
            </div>
        </div>

        <a href="google_login.php" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
            Google
        </a>
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
          <input
            type="text"
            name="name"
            placeholder="Họ và tên"
            required
            class="w-full px-4 py-3 border rounded-lg"
          />
          <input
            type="email"
            name="email"
            placeholder="Email"
            required
            class="w-full px-4 py-3 border rounded-lg"
          />
          <input
            type="text"
            name="phone"
            placeholder="Số điện thoại"
            required
            class="w-full px-4 py-3 border rounded-lg"
          />
          <input
            type="password"
            name="password"
            placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
            required
            class="w-full px-4 py-3 border rounded-lg"
          />
          <input
            type="password"
            name="confirm_password"
            placeholder="Nhập lại mật khẩu"
            required
            class="w-full px-4 py-3 border rounded-lg"
          />
          <input type="checkbox" id="agreeTerms" required class="mr-2" />
          <span class="text-sm text-gray-700">
            Tôi đồng ý với
            <a href="#" class="text-purple-600 hover:underline"
              >Điều khoản sử dụng</a
            >
            và
            <a href="#" class="text-purple-600 hover:underline"
              >Chính sách bảo mật</a
            >
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
        <div
          class="bg-green-100 text-green-600 px-4 py-2 rounded mt-3 text-center"
        >
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
<script src="search.js"></script>
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