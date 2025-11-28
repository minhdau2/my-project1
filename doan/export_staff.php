<?php

require_once 'config/db.php';
$conn->set_charset("utf8mb4");
if (ob_get_length()) ob_end_clean();
$filename = "DanhSachNhanVien_" . date('Y-m-d') . ".xls";


header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; 

echo '<table border="1">';
echo '<tr style="background-color: #f2f2f2; font-weight:bold;">
        <th>ID</th>
        <th>Tên Nhân Viên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Vai trò</th>
        <th>Ngày tạo</th>
      </tr>';

$sql = "SELECT id, name, email, phone, role, created_at FROM admin ORDER BY id DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['email'] . '</td>
            <td style="mso-number-format:\'\@\'">' . $row['phone'] . '</td>
            <td>' . $row['role'] . '</td>
            <td>' . $row['created_at'] . '</td>
          </tr>';
}
echo '</table>';
exit();
?>