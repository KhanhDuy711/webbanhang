<?php

include("../../db/MySQLConnect.php");

$query = "SELECT * FROM product WHERE pstatus=1";
$result = $connect->query($query);

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set timezone to Vietnam
$printDate = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>In danh sách sản phẩm</title>
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
    </div>
       
</header>
<main>
    <table>
        <h1>DANH SÁCH SẢN PHẨM</h1>
        <thead>
            <tr style="text-align: center;">
                <th scope="col">Mã sản phẩm</th>
                <th scope="col">Tên sản phẩm</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Giá niêm yết</th>
                <th scope="col">Giá bán</th>
                <th scope="col">Số lượng</th>
            </tr>
        </thead>
        <tbody >
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '<tr style="text-align: center;">';
                echo "<td style='text-align: center;'>{$row['pid']}</td>";
                echo "<td style='text-align: center;'>{$row['pname']}</td>";
                echo "<td style='text-align: center;'>{$row['pdesc']}</td>";
                echo "<td style='text-align: center;'>{$row['poriginalprice']}</td>";
                echo "<td style='text-align: center;'>{$row['psellprice']}</td>";
                echo "<td style='text-align: center;'>{$row['pquantity']}</td>";
                
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
