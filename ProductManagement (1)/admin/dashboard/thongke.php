<?php

include "Connect.php";

$startDate = $_GET['start_date'] ?? date('Y-m-d');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Queries to get statistics
$today = date('Y-m-d');
$todayQuery = "SELECT COUNT(*) as orderCount, SUM(rmoney) as totalRevenue FROM receipt WHERE DATE(rdate) = '$today'";
$thisMonth = date('Y-m');
$thisYear = date('Y');

$monthQuery = "SELECT COUNT(*) as orderCount, SUM(rmoney) as totalRevenue FROM receipt WHERE DATE_FORMAT(rdate, '%Y-%m') = '$thisMonth' and rstatus = 'Hoàn thành'";
$yearQuery = "SELECT COUNT(*) as orderCount, SUM(rmoney) as totalRevenue FROM receipt WHERE DATE_FORMAT(rdate, '%Y') = '$thisYear'and rstatus = 'Hoàn thành'";

$statsQuery = "SELECT
                (SELECT COUNT(*) FROM customer) as totalAccounts,
                (SELECT COUNT(*) FROM product) as totalProducts,
                (SELECT COUNT(*) FROM categories) as totalCategories,
                (SELECT COUNT(*) FROM receipt) as totalReceipts,
                (SELECT COUNT(*) FROM sale) as totalPromotions";

$todayResult = $connect->query($todayQuery)->fetch_assoc();
$monthResult = $connect->query($monthQuery)->fetch_assoc();
$yearResult = $connect->query($yearQuery)->fetch_assoc();
$statsResult = $connect->query($statsQuery)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="background-color: #f1efef">
    <div class="container mt-5">
        <h2>Thống kê</h2>
        <form id="filterForm" class="row g-3 mb-4">
            <div class="col-md-5">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
            </div>
            <div class="col-md-5">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
            </div>
            <div class="col-md-5 align-self-end">
                <button type="button" id="getStatistics" class="btn btn-primary">Thống kê</button>
                <button type="button" id="clearFilter" class="btn btn-secondary">Xóa</button>
            </div>
        </form>

        <div id="statsContainer">
            <div class="row">
                <div class="col-md-4">
                    <div class="p-3 bg-light border">
                        <p>Doanh thu đã lọc</p>
                        <p>- Đơn hàng: <span id="filteredOrders">0</span></p>
                        <p>- Tổng cộng: <span id="filteredRevenue">0</span> VND</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light border">
                        <p>Doanh thu tháng này</p>
                        <p>- Đơn hàng: <?= $monthResult['orderCount'] ?></p>
                        <p>- Tổng cộng: <?= number_format($monthResult['totalRevenue'] ?? 0) ?> VND</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light border">
                        <p>Doanh thu năm nay</p>
                        <p>- Đơn hàng: <?= $yearResult['orderCount'] ?></p>
                        <p>- Tổng cộng: <?= number_format($yearResult['totalRevenue'] ?? 0) ?> VND</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="p-3 bg-light border">Tổng số tài khoản: <?= $statsResult['totalAccounts'] ?></div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light border">Tổng sản phẩm: <?= $statsResult['totalProducts'] ?></div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light border">Tổng số danh mục: <?= $statsResult['totalCategories'] ?></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="p-3 bg-light border">Tổng số đơn hàng: <?= $statsResult['totalReceipts'] ?></div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light border">Tổng số khuyến mại: <?= $statsResult['totalPromotions'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#getStatistics').click(function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                $.ajax({
                    url: 'get_stats.php',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        $('#filteredOrders').text(data.orderCount);
                        $('#filteredRevenue').text(new Intl.NumberFormat().format(data.totalRevenue));
                    }
                });
            });

            $('#clearFilter').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#filteredOrders').text('0');
                $('#filteredRevenue').text('0');
            });
        });
    </script>
</body>

</html>

<?php
// Đóng kết nối
$connect->close();
?>