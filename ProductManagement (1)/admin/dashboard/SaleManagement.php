<?php

if (!isset($_SESSION["sale_error"])){
    $_SESSION["sale_error"] = "";
}
include("../../db/MySQLConnect.php");

$query = "SELECT * FROM sale";
$result = $connect->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý chương trình khuyến mãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
        .button-edit, .button-delete, .button-Add, .button-Print {
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Quản lý chương trình khuyến mãi</h2>
    <center>
        <font color="red"><?php echo $_SESSION["sale_error"]; ?></font>
    </center>
    
    <!-- Ô tìm kiếm chương trình khuyến mãi -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Tìm kiếm chương trình khuyến mãi theo tên...">
    </div>
    
    <button type="button" class="btn btn-primary">
        <a class="button-Add" href="index.php?manage=Sale_add">Thêm mới chương trình khuyến mãi</a>
    </button>
    <button type="button" class="btn btn-secondary">
        <a class="button-Print" href="Sale_print.php" target="_blank">In danh sách chương trình</a>
    </button>
    
    <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
        <thead>
            <tr>
                <th scope="col">Mã chương trình</th>
                <th scope="col">Tên chương trình</th>
                <th scope="col">Phần trăm giảm giá</th>
                <th scope="col">Ngày bắt đầu</th>
                <th scope="col">Ngày kết thúc</th>
                <th scope="col">Sửa</th>
                <th scope="col">Xóa</th>
            </tr>
        </thead>
        <tbody id="saleTableBody">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['saleid']}</td>";
            echo "<td>{$row['salename']}</td>";
            echo "<td>{$row['salepercent']}%</td>";
            echo "<td>{$row['salebegin']}</td>";
            echo "<td>{$row['saleend']}</td>";
            echo "<td><button type='button' class='btn btn-warning'><a href='index.php?manage=Sale_edit&saleid={$row['saleid']}'>Sửa</a></button></td>";
            echo '<td><button type="button" class="btn btn-danger"><a onclick="return confirm(\'Bạn có chắc muốn xóa chương trình '. $row["salename"] .' không?\')" href="Sale_delete.php?saleid='. $row["saleid"] .'">Xóa</a></button></td>';
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Add jQuery to the page -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            $('#saleTableBody tr').each(function() {
                var saleName = $(this).find('td').eq(1).text().toLowerCase(); // Tên chương trình nằm ở cột thứ 2 (index 1)
                if (saleName.indexOf(searchValue) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>
</body>
</html>

<?php
// Đóng kết nối
$connect->close();
unset($_SESSION["sale_error"]);
?>
