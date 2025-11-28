<?php
session_start();
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CinemaBooking - Giới thiệu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 1.5s ease-in-out forwards;
    }
  </style>
  <script>
    setTimeout(() => {
      window.location.href = "index.php";
    }, 3000); 
  </script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-br from-purple-600 to-indigo-700 text-white">

  <div class="text-center fade-in">
    <h1 class="text-5xl font-extrabold mb-4">🎬 CinemaBooking</h1>
    <p class="text-lg mb-6">Trải nghiệm điện ảnh đỉnh cao, dễ dàng đặt vé chỉ với vài cú nhấp!</p>
    <div class="flex justify-center mb-4">
      <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-white border-opacity-80"></div>
    </div>
    <p class="text-sm opacity-90">Đang vào trang chủ...</p>
  </div>

</body>
</html>
