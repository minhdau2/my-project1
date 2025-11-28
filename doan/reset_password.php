<?php
session_start();
require 'config/db.php'; 

$DEBUG = false; 

function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = trim($_GET['token']);
    $token = urldecode($token);

    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    if (!$stmt) {
        if ($DEBUG) echo "Prepare error: " . $conn->error;
        echo "❌ Lỗi máy chủ, thử lại sau.";
        exit;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    if ($DEBUG) {

    }

    if ($data && strtotime($data['expires_at']) > time()) {

        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1" />
          <title>Đặt lại mật khẩu</title>
          <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="min-h-screen bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
        <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 w-[90%] max-w-md">
          <h2 class="text-2xl font-bold text-gray-800 mb-3 text-center">🔐 Đặt lại mật khẩu</h2>
          <p class="text-gray-600 text-sm text-center mb-6">
            Nhập mật khẩu mới của bạn bên dưới và xác nhận để hoàn tất.
          </p>

          <?php if (!empty($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= h($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <form action="reset_password.php" method="POST" class="space-y-4">
            <input type="hidden" name="token" value="<?= h($token) ?>">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                <input type="password" id="password" name="password" required minlength="6"
               placeholder="Nhập mật khẩu mới"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
              <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
               placeholder="Nhập lại mật khẩu"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Cập nhật mật khẩu</button>
          </form>

          <div class="text-center mt-4">
            <a href="login.php" class="text-sm text-blue-600">← Quay lại đăng nhập</a>
          </div>
        </div>

        <script>
        document.querySelector('form').addEventListener('submit', function(e){
          const p = document.getElementById('password').value;
          const c = document.getElementById('confirm_password').value;
          if (p !== c) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp.');
          }
        });
        </script>
        </body>
        </html>
        <?php
        exit;
    } else {
        // không hợp lệ / hết hạn
        if ($DEBUG) {
            echo "<p style='color:red'>DEBUG: token=" . h($token) . "</p>";
            if ($data) {
                echo "<pre>expires_at=" . h($data['expires_at']) . "</pre>";
            } else {
                echo "<p style='color:orange'>DEBUG: token không tìm thấy trong DB</p>";
            }
        }
        echo "❌ Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) $errors[] = "Mật khẩu xác nhận không khớp.";
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
        $errors[] = "Mật khẩu phải >=6 ký tự, có chữ hoa, chữ thường và số.";
    }
    if (empty($token)) $errors[] = "Token không hợp lệ.";

    if ($errors) {
        $_SESSION['error'] = implode(" | ", $errors);
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }

    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Lỗi server.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$row) {
        $_SESSION['error'] = "Token không tồn tại hoặc đã dùng.";
        header("Location: reset_password.php");
        exit;
    }
    if (strtotime($row['expires_at']) <= time()) {
        $_SESSION['error'] = "Token đã hết hạn.";
        header("Location: reset_password.php");
        exit;
    }

    $email = $row['email'];
    $newHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Lỗi cập nhật người dùng.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }
    $stmt->bind_param("ss", $newHash, $email);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Lỗi khi cập nhật mật khẩu: " . $stmt->error;
        $stmt->close();
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
    }

    echo "<div style='padding:20px;text-align:center;'>✅ Mật khẩu đã được đặt lại thành công. <a href='login.php'>Đăng nhập ngay</a></div>";
    exit;
}

echo "Yêu cầu không hợp lệ.";
exit;
