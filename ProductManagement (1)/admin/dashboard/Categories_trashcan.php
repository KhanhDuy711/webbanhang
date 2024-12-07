<?php

if (!isset($_SESSION["cate_error"])){
    $_SESSION["cate_error"]="";
}
include("../../db/MySQLConnect.php");

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)


$query = "SELECT * FROM categories where catestatus=0";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thùng rác</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        a{
            text-decoration: none;
        }
        .button-edit,.button-delete,.button-Add{
            color:black;
            font-weight: bold;
        }

    </style>
    
</head>
<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Quản lý danh mục</h2>
    <center>
        <font color=red><?php echo $_SESSION["cate_error"]; ?></font>
    </center>
    <button type="button" class="btn btn-primary"><a class="button-Add" href="index.php?manage=categories">Trở về</a></button>
    
    <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
        <tr>
            <th  scope="col">Mã danh mục</th>
            <th  scope="col">Tên danh mục</th>
            <th  scope="col">Mô tả</th>
            <th  scope="col">Trạng thái</th>
            <th  scope="col">Khôi phục</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['cateid']}</td>";
            echo "<td>{$row['catename']}</td>";
            echo "<td>{$row['catedesc']}</td>";
            echo "<td>Đã xóa</td>";
            echo '<td><button type="button" class="btn btn-danger"><a onclick="return confirm(\'Bạn có chắc muốn khôi phục danh mục: '. $row["catename"] .' không?\')" href="action/Categories_undo.php?cateid='. $row["cateid"] .'">Khôi phục</a></button></td>';

            echo "</tr>";
        }
        ?>
    </table>
  
</body>
</html>

<?php
// Đóng kết nối
$connect->close();
unset($_SESSION["cate_error"]);
?>
