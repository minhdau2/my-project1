<?php
require_once 'config/db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $totalAmount = floatval($_POST['total_amount'] ?? 0);
    $currentMovieId = intval($_POST['movie_id'] ?? 0);
    $userId = $_SESSION['user']['id'] ?? 0; 

    if (empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM promotions WHERE code = ? AND is_active = 1");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $promotion = $stmt->get_result()->fetch_assoc();

    if (!$promotion) {
        echo json_encode(['success' => false, 'message' => 'Mã không tồn tại hoặc hết hạn.']);
        exit;
    }

    $now = date('Y-m-d H:i:s');
    if (($promotion['start_date'] && $now < $promotion['start_date']) || 
        ($promotion['end_date'] && $now > $promotion['end_date'])) {
        echo json_encode(['success' => false, 'message' => 'Mã đã hết hạn sử dụng.']);
        exit;
    }
    
    if ($totalAmount < $promotion['min_order_value']) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng chưa đủ giá trị tối thiểu.']);
        exit;
    }

    if (!empty($promotion['applicable_movie_id']) && $promotion['applicable_movie_id'] != $currentMovieId) {
        echo json_encode(['success' => false, 'message' => 'Mã không áp dụng cho phim này.']);
        exit;
    }
    if ($userId > 0) {
        $stmtCheck = $conn->prepare("
            SELECT COUNT(*) as usage_count 
            FROM booking_history 
            WHERE user_id = ? AND promotion_id = ?
        ");
        $stmtCheck->bind_param("ii", $userId, $promotion['id']);
        $stmtCheck->execute();
        $history = $stmtCheck->get_result()->fetch_assoc();

        if ($history['usage_count'] >= $promotion['usage_limit_per_user']) {
            echo json_encode(['success' => false, 'message' => 'Bạn đã sử dụng mã này rồi (Giới hạn: '.$promotion['usage_limit_per_user'].' lần).']);
            exit;
        }
    } else {
        if ($promotion['usage_limit_per_user'] > 0) {
             echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng mã này.']);
             exit;
        }
    }
    // ============================================================

    $discountAmount = ($promotion['discount_type'] === 'percent') 
        ? ($totalAmount * $promotion['discount_value']) / 100
        : $promotion['discount_value'];

    if ($discountAmount > $totalAmount) $discountAmount = $totalAmount;

    echo json_encode([
        'success' => true,
        'message' => 'Áp dụng mã thành công!',
        'discount_amount' => $discountAmount,
        'new_total' => $totalAmount - $discountAmount,
        'promotion_id' => $promotion['id'], 
        'promotion_code' => $promotion['code']
    ]);
}
?>