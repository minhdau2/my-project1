<!-- Biểu đồ Doanh thu theo tháng -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Biểu đồ doanh thu theo tháng</h5>
    </div>
    <div class="card-body">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<?php
// Kết nối database
include 'config.php'; // nếu bạn đã include rồi thì bỏ dòng này

// Lấy dữ liệu doanh thu theo tháng
$query = "
    SELECT 
        MONTH(booking_date) AS month, 
        SUM(total_amount) AS total_revenue
    FROM booking_history
    GROUP BY MONTH(booking_date)
    ORDER BY MONTH(booking_date)
";
$result = mysqli_query($conn, $query);

$months = [];
$revenues = [];

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = 'Tháng ' . $row['month'];
    $revenues[] = $row['total_revenue'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?php echo json_encode($revenues); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Tổng doanh thu theo tháng (Booking History)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            }
        }
    });
</script>
