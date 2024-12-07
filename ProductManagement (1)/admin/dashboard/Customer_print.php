<?php

include "../../db/Connect.php";

$query = "SELECT * FROM customer";
$result = $conn->query($query);

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set timezone to Vietnam
$printDate = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>In danh sách khách hàng</title>
    <link rel="stylesheet" href="../css/inhoadon.css" media="all" />
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
<header class="clearfix">
    <div id="company" class="clearfix">
        <div>LOVE SHOP</div>
        <div>Triều Khúc,<br /> HN, VN</div>
        <div>0326918392</div>
        <div><a href="mailto:anhdat26102002@gmail.com">anhdat26102002@gmail.com</a></div>
        <div><span>Ngày in:</span> <?php echo $printDate; ?></div>
    </div>
</header>
<main>
    <table>
        <h1>DANH SÁCH KHÁCH HÀNG</h1>
        <thead>
            <tr style="text-align: center;">
                <th scope="col">Mã khách hàng</th>
                <th scope="col">Tên khách hàng</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">SĐT</th>
                <th scope="col">Email</th>
                <th scope="col">Tài khoản</th>
                <th scope="col">Mật khẩu</th>
                <th scope="col">Tình trạng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='text-align: center;'>";
                echo "<td>{$row['cid']}</td>";
                echo "<td>{$row['cname']}</td>";
                echo "<td>{$row['caddress']}</td>";
                echo "<td>{$row['cphone']}</td>";
                echo "<td>{$row['cemail']}</td>";
                echo "<td>{$row['caccount']}</td>";
                echo "<td>{$row['cpassword']}</td>";

                $statusDisplay = ($row['cstatus'] == 0) ? "Khóa" : "Hoạt động";
                echo "<td>{$statusDisplay}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</main>
<footer>
    &copy; 2024 LOVE SHOP. All rights reserved.
</footer>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
