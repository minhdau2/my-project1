<?php
require 'config/db.php';
$roomId = intval($_GET['roomId']);
$sql = "SELECT DISTINCT m.id, m.title 
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.id
        WHERE s.room_id = $roomId";
$result = $conn->query($sql);
echo "<option value=''>Chọn phim</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['title']}</option>";
}
$conn->close();
?>
