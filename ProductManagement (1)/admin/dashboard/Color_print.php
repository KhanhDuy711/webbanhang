<?php

include("../../db/MySQLConnect.php");

$query = "SELECT * FROM color";
$result = $connect->query($query);

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set timezone to Vietnam
$printDate = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>In danh sách màu sắc</title>
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
        <div><span>Ngày in</span> <?php echo $printDate; ?></div>
    </div>
</header>
<main>
    <table>
        <h1>DANH SÁCH MÀU SẮC</h1>
        <thead>
            <tr style="text-align: center;">
                <th scope="col">Mã màu sắc</th>
                <th scope="col">Tên màu sắc</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '<tr style="text-align: center;">';
                echo "<td style='text-align: center;'>{$row['colorid']}</td>";
                echo "<td style='text-align: center;'>{$row['colorname']}</td>";
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
$connect->close();
?>
