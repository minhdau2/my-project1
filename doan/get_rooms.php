<?php
require 'config/db.php';
$cinemaId = intval($_GET['cinemaId']);
$sql = "SELECT id, name FROM rooms WHERE cinema_id = $cinemaId";
$result = $conn->query($sql);
echo "<option value=''>Chọn phòng</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
$conn->close();
?>
