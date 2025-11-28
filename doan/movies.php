<?php
session_start();


require_once 'config/db.php';

$genreFilter = $_GET['genre'] ?? '';
$q = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM movies WHERE 1=1";
$params = [];
$types = "";

// Lọc thể loại
if ($genreFilter !== '') {
    $sql .= " AND genre LIKE ?";
    $params[] = "%$genreFilter%";
    $types .= "s";
}

// Tìm kiếm
if ($q !== '') {
    $terms = preg_split('/\s+/', $q);
    $searchConditions = [];
    foreach ($terms as $term) {
        $searchConditions[] = "(title LIKE ? OR description LIKE ?)";
        $params[] = "%$term%";
        $params[] = "%$term%";
        $types .= "ss";
    }
    $sql .= " AND " . implode(" AND ", $searchConditions);
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CinemaBooking - Danh Sách Phim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css" />
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
              <div class="relative group">
                <button class="px-3 py-2 hover:text-gray-300 flex items-center">
                    Phim
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0  duration-300 hidden group-hover:block z-50">
                  <a href="dang_chieu.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Phim Đang Chiếu</a>
                  <a href="sapchieu.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Phim Sắp Chiếu</a>
                </div>
            </div>

              <a
                href="booking.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
                >Đặt Vé</a
              >
              <a
                href="history.php"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors"
                >Lịch Sử</a
              >
              <div  class="relative">
                <input
                  type="text" 
                  id="searchInput" 
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
                        <a href="admin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Quản trị</a>
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
      <div class="flex justify-between items-center mb-6 scroll-animate">
        <h2 class="text-3xl font-bold scroll-animate-left">Danh Sách Phim</h2>
        <div class="flex space-x-4 scroll-animate-right">
          <select
            id="genre-filter"
            class="px-4 py-2 border rounded-lg"
            onchange="window.location.href='movies.php?genre=' + encodeURIComponent(this.value);"
          >
            <option value="">Tất cả thể loại</option>
              <option value="action" <?= (isset($_GET['genre']) && $_GET['genre'] === 'action') ? 'selected' : '' ?>>Hành động</option>
              <option value="comedy" <?= (isset($_GET['genre']) && $_GET['genre'] === 'comedy') ? 'selected' : '' ?>>Hài kịch</option>
              <option value="drama" <?= (isset($_GET['genre']) && $_GET['genre'] === 'drama') ? 'selected' : '' ?>>Chính kịch</option>
              <option value="horror" <?= (isset($_GET['genre']) && $_GET['genre'] === 'horror') ? 'selected' : '' ?>>Kinh dị</option>
              <option value="romance" <?= (isset($_GET['genre']) && $_GET['genre'] === 'romance') ? 'selected' : '' ?>>Tình cảm</option>
              <option value="biography" <?= (isset($_GET['genre']) && $_GET['genre'] === 'biography') ? 'selected' : '' ?>>Tiểu sử</option>
              <option value="animation" <?= (isset($_GET['genre']) && $_GET['genre'] === 'animation') ? 'selected' : '' ?>>Hoạt hình</option>
              <option value="sci-fi" <?= (isset($_GET['genre']) && $_GET['genre'] === 'sci-fi') ? 'selected' : '' ?>>Khoa học viễn tưởng</option>
              <option value="Documentary" <?= (isset($_GET['genre']) && $_GET['genre'] === 'Documentary') ? 'selected' : '' ?>>Tài liệu</option>
          </select>
        </div>
      </div>

      
      <div class="mb-8 scroll-animate">
      <?php if ($q !== ''): ?>
        <h3 class="text-2xl font-bold mb-2 text-gray-800">Kết quả tìm kiếm cho: <span class="text-purple-600">"<?php echo htmlspecialchars($q); ?>"</span></h3>
      <?php else: ?>
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Phim Đang Chiếu</h3>
      <?php endif; ?>

        <div id="moviesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ">
          <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="movie-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer scroll-animate stagger-animation">';
                    
                    // Link đến trang chi tiết
                    echo '<a href="chitietphim.php?id=' . $row["id"] . '" class="block h-48 overflow-hidden transition duration-700 ease-in-out">';
                    if (!empty($row["poster"])) {
                        echo '<img src="' . htmlspecialchars($row["poster"]) . '" alt="' . htmlspecialchars($row["title"]) . '" class="object-cover w-full h-full hover:scale-105 transition-transform duration-500">';
                    } else {
                        echo '<div class="bg-gradient-to-br from-purple-400 to-blue-500 w-full h-full flex items-center justify-center text-6xl text-white">🎬</div>';
                    }
                    echo '</a>';

                    echo '<div class="p-4">';
                    echo '<a href="chitietphim.php?id=' . $row["id"] . '" class="font-bold text-lg mb-2 block text-gray-800 hover:text-purple-600 transition-colors">';
                    echo htmlspecialchars($row["title"]);
                    echo '</a>';
                    echo '<p class="text-gray-600 text-sm mb-2">' . htmlspecialchars(substr($row["description"], 0, 100)) . '...</p>';
                    echo '<div class="flex justify-between items-center">';
                    echo '<span class="text-purple-600 font-semibold">' . $row["duration"] . ' phút</span>';
                    echo '<span class="text-green-600 font-bold">' . number_format($row["price"], 0, ",", ".") . ' VNĐ</span>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo "<p class='text-center'>Không có phim nào để hiển thị.</p>";
            }
            ?>

        </div>
      </div>
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

function loadMovies(query = '') {
  fetch('search_movie.php?q=' + encodeURIComponent(query))
    .then(res => res.text())
    .then(html => {
      document.getElementById('moviesContainer').innerHTML = html;
      initScrollAnimations();
    })
    .catch(err => {
      console.error('Lỗi khi tải phim:', err);
    });
}




const input = document.getElementById('searchInput');
let typingTimer;
input.addEventListener('keyup', () => {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(() => {
    loadMovies(input.value.trim());
  }, 400); 
});
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
