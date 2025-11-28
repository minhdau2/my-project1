<?php
require_once 'config/db.php';

$movieId = intval($_GET['movieId'] ?? 0);
if ($movieId <= 0) {
    echo "<option value=''>Không có rạp</option>";
    exit;
}

$stmt = $conn->prepare("
    SELECT DISTINCT c.id, c.name 
    FROM cinemas c
    JOIN rooms r ON r.cinema_id = c.id
    JOIN showtimes s ON s.room_id = r.id
    WHERE s.movie_id = ?
    ORDER BY c.name
");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();

echo "<option value=''>Chọn rạp</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>