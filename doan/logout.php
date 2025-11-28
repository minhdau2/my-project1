<?php
session_start();

session_unset();
session_destroy();

session_start();
$_SESSION['success'] = "Bạn đã đăng xuất thành công!";

header("Location: index.php");
exit;
?>
