<?php
session_start();
 
// ✅ Kiểm tra xem người dùng đã xem intro chưa
if (!isset($_SESSION['intro_shown'])) {
    $_SESSION['intro_shown'] = true; // đánh dấu đã xem intro
    header("Location: intro.php"); // chuyển hướng sang intro.php
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,
initial-scale=1.0" />
        <title>CinemaBooking - Trang Chủ</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="styles.css"
/>
    </head>
    <body class="bg-gray-50">
        <nav class="gradient-bg text-white
shadow-lg">
            <div class="max-w-7xl
mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex
justify-between items-center h-16">
                    <div class="flex
items-center">
                        <h1 class="text-2xl
font-bold">🎬 CinemaBooking</h1>
                    </div>
                    <div class="hidden
md:block">
                        <div class="ml-10
flex items-baseline space-x-4">
                            <a href="index.php"
class="nav-link px-3 py-2 rounded-md text-sm font-medium bg-white
bg-opacity-20">Trang Chủ</a>
                            <a href="movies.php"
class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white
hover:bg-opacity-20 transition-colors">Phim</a>
                            <a href="booking.php"
class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white
hover:bg-opacity-20 transition-colors">Đặt Vé</a>
                            <a href="history.php"
class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white
hover:bg-opacity-20 transition-colors">Lịch Sử</a>
                            <div class="relative">
                                <input
type="text" id="searchInput" name="q" placeholder="Tìm
kiếm phim..." class="pl-10 pr-4 py-2 rounded-full text-gray-800
focus:outline-none focus:ring-2 focus:ring-white" />
                                <svg
class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform
-translate-y-1/2" fill="none" stroke="currentColor" viewBox="0
0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0
0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
 
                    <div class="flex
items-center space-x-4">
                        <?php if (isset($_SESSION['user'])):
?>
                            <div
class="relative group">
                                    <button class="bg-white text-purple-600 px-4 py-2
rounded-lg font-medium">
                                        Xin chào, <?= htmlspecialchars($_SESSION['user']['name'])
?> ▼
                                    </button>
                                    <div class="absolute right-0 mt-1 w-48 bg-white
rounded-lg shadow-lg hidden group-hover:block z-50">
                                        <a href="profile.php" class="block
px-4 py-2 text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                                        <a href="history.php" class="block
px-4 py-2 text-gray-700 hover:bg-gray-100">Lịch sử đặt vé</a>
                                        <a href="logout.php" class="block
px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                                    </div>
                            </div>
                        <?php elseif
(isset($_SESSION['admin'])): ?>
                            <div
class="relative group">
                                    <button class="bg-white text-purple-600 px-4 py-2
rounded-lg font-medium">
                                        Xin chào, Admin <?= htmlspecialchars($_SESSION['admin']['name'])
?> ▼
                                    </button>
                                    <div class="absolute right-0 mt-1 w-48 bg-white
rounded-lg shadow-lg hidden group-hover:block z-50">
                                        <a href="dashboard.php" class="block
px-4 py-2 text-gray-700 hover:bg-gray-100">Quản trị</a>
                                        <a href="logout.php" class="block
px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                                    </div>
                            </div>
                        <?php else: ?>
                            <button
onclick="showLoginModal()" class="bg-white text-purple-600 px-4
py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Đăng Nhập
                            </button>
                        <?php endif;
?>
                    </div>
                </div>
            </div>
        </nav>
 
        <main class="max-w-7xl mx-auto px-4
sm:px-6 lg:px-8 py-8">
 
            <section class="bg-white
rounded-2xl shadow-md p-8 mb-8 scroll-animate">
                <div class="grid
grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-4">
                        <h3 class="text-3xl
font-bold text-purple-700">Giới thiệu về CinemaBooking</h3>
                        <p class="text-gray-700
leading-relaxed">
                            <strong>CinemaBooking</strong>
là hệ thống đặt vé xem phim trực tuyến giúp bạn dễ dàng chọn phim, rạp chiếu,
chỗ ngồi và thanh toán chỉ trong vài bước.
                            Chúng
tôi mang đến trải nghiệm đặt vé nhanh chóng, tiện lợi và bảo mật tuyệt đối.
                        </p>
                        <ul class="list-disc
list-inside text-gray-600">
                            <li>Đặt
vé mọi lúc, mọi nơi</li>
                            <li>Thanh
toán an toàn, đa dạng hình thức</li>
                            <li>Cập
nhật lịch chiếu liên tục theo thời gian thực</li>
                            <li>Tích
điểm và nhận ưu đãi hấp dẫn</li>
                        </ul>
                        <a href="movies.php"
class="inline-block mt-4 bg-purple-600 text-white px-6 py-3 rounded-lg
font-semibold hover:bg-purple-700 transition-colors">
                            Bắt
đầu đặt vé ngay
                        </a>
                    </div>
                    <div class="flex
justify-center">
                        <img src="img/anhnenbanner.png"
alt="Giới thiệu CinemaBooking"
                                class="w-full max-w-md rounded-xl shadow-md hover:scale-105
transition-transform duration-500">
                    </div>
                </div>
            </section>
 
            <div class="mb-8
scroll-animate">
                <h3 class="text-2xl
font-bold mb-6 scroll-animate-left">Phim Đang Chiếu</h3>
                <div class="grid
grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php
                        // Nếu file db.php không có lỗi, đoạn code này sẽ chạy
                        require_once 'config/db.php';
                        $sql = "SELECT
id, title, genre, duration, price, description, poster FROM movies LIMIT
4";
                        
                        // Khởi tạo $result để tránh lỗi nếu query thất bại
                        $result = null;
                        if (isset($conn)) {
                            $result = $conn->query($sql);
                        }
 
                        if ($result
&& $result->num_rows > 0) {
                            while($row
= $result->fetch_assoc()) {
                                // Kiểm tra các biến để đảm bảo chúng tồn tại
                                $movie_id = htmlspecialchars($row["id"] ?? '');
                                $title = htmlspecialchars($row["title"] ?? 'Không rõ tên');
                                $duration = htmlspecialchars($row["duration"] ?? 'N/A');
                                $price = number_format($row["price"] ?? 0, 0, ",", ".");
                                $description = htmlspecialchars(substr($row["description"] ?? '', 0, 100)) . '...';
                                $poster = htmlspecialchars($row["poster"] ?? '');
 
                                echo
'<div class="movie-card bg-white rounded-xl shadow-md overflow-hidden
cursor-pointer scroll-animate stagger-animation">';
                                echo
' 	<a href="booking.php?movie_id=' . $movie_id . '"
class="block h-48 overflow-hidden transition duration-700
ease-in-out">';
                                if
(!empty($poster)) {
                                    echo ' 	 	<img src="' . $poster
. '" alt="' . $title . '"
class="object-cover w-full h-full hover:scale-105 transition-transform
duration-500">';
                                }
else {
                                    echo ' 	 	<div class="bg-gradient-to-br
from-purple-400 to-blue-500 w-full h-full flex items-center justify-center
text-6xl text-white">🎬</div>';
                                }
                                echo
' 	</a>';
                                echo
' 	<div class="p-4">';
                                echo
' 	 	<a href="booking.php?movie_id=' . $movie_id .
'" class="font-bold text-lg mb-2 block text-gray-800
hover:text-purple-600 transition-colors">';
                                echo
                                    $title;
                                echo
' 	 	</a>';
                                echo
' 	 	<p class="text-gray-600 text-sm mb-2">' .
$description . '</p>';
                                echo
' 	 	<div class="flex justify-between items-center">';
                                echo
' 	 	 	<span class="text-purple-600
font-semibold">' . $duration . ' phút</span>';
                                echo
' 	 	 	<span class="text-green-600 font-bold">'
. $price . '
VNĐ</span>';
                                echo
' 	 	</div>';
                                echo
' 	</div>';
                                echo
'</div>';
                            }
                        } else {
                            echo "<p
class='text-center'>Không có phim nào để hiển thị hoặc lỗi truy vấn.</p>";
                        }
                    ?>
                </div>
            </div>
 
            <div class="grid grid-cols-1
md:grid-cols-3 gap-6 mb-8 scroll-animate">
                <div class="bg-white
p-6 rounded-xl shadow-md text-center scroll-animate-left">
                    <div class="text-3xl
font-bold text-purple-600 mb-2">6</div>
                    <div class="text-gray-600">Phim
Đang Chiếu</div>
                </div>
                <div class="bg-white
p-6 rounded-xl shadow-md text-center scroll-animate">
                    <div class="text-3xl
font-bold text-purple-600 mb-2">50K+</div>
                    <div class="text-gray-600">Khách
Hàng Hài Lòng</div>
                </div>
                <div class="bg-white
p-6 rounded-xl shadow-md text-center scroll-animate-right">
                    <div class="text-3xl
font-bold text-purple-600 mb-2">2</div>
                    <div class="text-gray-600">Rạp
Chiếu Phim</div>
                </div>
            </div>
        </main>
 
        <div id="login-modal" class="fixed
inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white
rounded-xl p-8 max-w-md w-full mx-4">
                <div class="flex
justify-between items-center mb-6">
                    <h3 class="text-2xl
font-bold">Đăng Nhập</h3>
                    <button onclick="hideLoginModal()"
class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6
h-6" fill="none" stroke="currentColor" viewBox="0
0 24 24">
                            <path
stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6
18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="login.php"
method="POST" class="space-y-4">
                    <input type="email"
name="email" placeholder="Email" class="w-full px-4
py-3 border rounded-lg" required />
                    <input type="password"
name="password" placeholder="Mật khẩu" class="w-full
px-4 py-3 border rounded-lg" required />
                    <button type="submit"
class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold
hover:bg-purple-700 transition-colors">
                        Đăng Nhập
                    </button>
                </form>
 
                <?php if (isset($_SESSION['error'])):
?>
                <div class="bg-red-100
text-red-600 px-4 py-2 rounded mt-3 text-center">
                    <?=
htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php endif; ?>
 
                <div class="text-center
mt-4">
                    <a href="#"
onclick="openRegister()" class="text-purple-600
hover:underline">
                        Chưa có tài khoản?
Đăng ký ngay
                    </a>
                </div>
            </div>
        </div>
 
        <div id="Register-modal" class="fixed
inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white
rounded-xl p-8 max-w-md w-full mx-4">
                <div class="flex
justify-between items-center mb-6">
                    <h3 class="text-2xl
font-bold">Đăng Ký</h3>
                    <button onclick="hideRegisterModal()"
class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6
h-6" fill="none" stroke="currentColor" viewBox="0
0 24 24">
                            <path
stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6
18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
 
                <form action="register.php"
method="POST" class="space-y-4">
                    <input type="text"
name="name" placeholder="Họ và tên" required class="w-full
px-4 py-3 border rounded-lg" />
                    <input type="email"
name="email" placeholder="Email" required class="w-full
px-4 py-3 border rounded-lg" />
                    <input type="text"
name="phone" placeholder="Số điện thoại" required class="w-full
px-4 py-3 border rounded-lg" />
                    <input type="password"
name="password" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
required class="w-full px-4 py-3 border rounded-lg" />
                    <input type="password"
name="confirm_password" placeholder="Nhập lại mật khẩu" required
class="w-full px-4 py-3 border rounded-lg" />
                    <input type="checkbox"
id="agreeTerms" required class="mr-2" />
                    <span class="text-sm
text-gray-700">
                        Tôi đồng ý với
                        <a href="#"
class="text-purple-600 hover:underline">Điều khoản sử dụng</a>
                        và
                        <a href="#"
class="text-purple-600 hover:underline">Chính sách bảo mật</a>
                    </span>
                    <button type="submit"
class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold
hover:bg-purple-700 transition-colors">
                        Đăng ký
                    </button>
                </form>
 
                <?php if (isset($_SESSION['error'])):
?>
                <div class="bg-red-100
text-red-600 px-4 py-2 rounded mt-3 text-center">
                    <?=
htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']);
?>
                <?php endif; ?>
 
                <?php if (isset($_SESSION['success'])):
?>
                <div class="bg-green-100
text-green-600 px-4 py-2 rounded mt-3 text-center">
                    <?=
htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']);
?>
                <?php endif; ?>
 
                <div class="text-center
mt-4">
                    <a href="#"
onclick="openLogin()" class="text-purple-600
hover:underline">
                        Đã có tài khoản?
Đăng nhập ngay
                    </a>
                </div>
            </div>
        </div>
 
        <script src="common.js"></script>
        <script src="search.js"></script>
 
        <?php if (isset($_SESSION['error'])): ?>
            <script>
                document.addEventListener("DOMContentLoaded",
function() {
                    let modal =
document.getElementById("login-modal");
                    if (modal) {
                        modal.classList.remove("hidden");
                        modal.classList.add("flex");
                    }
                });
            </script>
        <?php endif; ?>
 
        <?php if (isset($_SESSION['show_register'])
&& $_SESSION['show_register']): ?>
            <script>
                document.addEventListener("DOMContentLoaded",
function() {
                    let modal =
document.getElementById("Register-modal");
                    if (modal) {
                        modal.classList.remove("hidden");
                        modal.classList.add("flex");
                    }
                });
            </script>
        <?php endif; ?>
 
        <footer class="bg-gray-900 text-gray-300
mt-12">
    <div class="max-w-7xl mx-auto px-6 py-12
grid grid-cols-1 md:grid-cols-3 gap-10">
 
        <div>
            <h3 class="text-xl font-bold
text-white mb-4">🎬 CinemaBooking</h3>
            <p class="text-gray-400
leading-relaxed">
                Hệ thống đặt vé xem phim trực
tuyến nhanh chóng – tiện lợi – bảo mật.
            </p>
            <p class="text-gray-500 mt-3
text-sm">
                © 2025 CinemaBooking. All
rights reserved.
            </p>
        </div>
 
        <div>
            <h3 class="text-xl
font-semibold text-white mb-4">Điều khoản sử dụng</h3>
            <ul class="space-y-2">
                <li><a href="term.php"
class="hover:text-white transition">Điều Khoản Chung</a></li>
                <li><a href="transaction-terms.php"
class="hover:text-white transition">Điều Khoản Giao Dịch</a></li>
                <li><a href="payment-policy.php"
class="hover:text-white transition">Chính Sách Giao Dịch</a></li>
                <li><a href="privacy-policy.php"
class="hover:text-white transition">Chính Sách Bảo Mật</a></li>
                <li><a href="faq.php"
class="hover:text-white transition">Câu Hỏi Thường Gặp</a></li>
            </ul>
        </div>
 
        <div>
            <h3 class="text-xl
font-semibold text-white mb-4">Thông Tin Liên Hệ</h3>
            <ul class="space-y-2">
                <li class="flex
items-center gap-2">
                    <span class="text-purple-400">📍</span>
12 Nguyễn Văn Bảo, Gò Vấp, TP.HCM
                </li>
                <li class="flex
items-center gap-2">
                    <span class="text-purple-400">📞</span>
0123 456 789
                </li>
                <li class="flex
items-center gap-2">
                    <span class="text-purple-400">📧</span>
support@cinemabooking.vn
                </li>
            </ul>
 
            <div class="flex items-center gap-4
mt-4">
                
                <a href="https://www.facebook.com/hien.ly.448665" class="transition
duration-300 hover:opacity-75">
                    <img src="img/facebook.png"
alt="Facebook" class="w-8 h-8 rounded-lg"/>
                </a>
                
                <a href="https://www.youtube.com/channel/UCheARmYBH_GLVlDljLHBSmA" class="transition
duration-300 hover:opacity-75">
                    <img src="img/youtube.png"
alt="YouTube" class="w-8 h-8 rounded-lg"/>
                </a>
                
                <a href="https://www.instagram.com/lhinne_/" class="transition
duration-300 hover:opacity-75">
                    <img src="img/instagram.jfif"
alt="Instagram" class="w-8 h-8 rounded-lg"/>
                </a>
                
                <a href="https://zalo.me/0346468252" class="transition
duration-300 hover:opacity-75">
                    <img src="img/zalo.png"
alt="Zalo" class="w-8 h-8 rounded-lg"/>
                </a>
                
            </div>
        </div>
 
    </div>
 
    <div class="bg-gray-800 text-center py-4
text-gray-400 text-sm">
        Phát triển bởi <span class="text-purple-400
font-medium">CinemaBooking Team</span>
    </div>
</footer>
 
    </body>
</html>