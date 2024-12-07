<?php

if (!isset($_SESSION["color_error"])){
    $_SESSION["color_error"]="";
}
include("../../db/MySQLConnect.php");

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)

$query = "SELECT * FROM color";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý màu sắc</title>
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
    <div class="container">
        <h2 style="margin-top:10px">Quản lý màu sắc</h2>
        <center>
            <font color=red><?php echo $_SESSION["color_error"]; ?></font>
        </center>
        
        <!-- Ô tìm kiếm màu sắc -->
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Tìm kiếm màu sắc theo tên...">
        </div>

        <button type="button" class="btn btn-primary">
            <a class="button-Add" href="index.php?manage=Color_add">Thêm màu</a>
        </button>
        <button type="button" class="btn btn-secondary">
            <a class="button-Print" href="Color_print.php" target="_blank">In danh sách màu sắc</a>
        </button>

        <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
            <thead>
                <tr>
                    <th scope="col">Mã màu sắc</th>
                    <th scope="col">Tên màu sắc</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody id="colorTableBody">
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['colorid']}</td>";
                    echo "<td>{$row['colorname']}</td>";
                    echo "<td><button type='button' class='btn btn-warning'><a class='button-edit' href='index.php?manage=Color_edit&colorid={$row['colorid']}'>Sửa</a></button></td>";
                    echo '<td><button type="button" class="btn btn-danger"><a class="button-delete" onclick="return confirm(\'Bạn có chắc muốn xóa màu '. $row["colorname"] .' không?\')" href="Color_delete.php?colorid='. $row["colorid"] .'">Xóa</a></button></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Thêm jQuery vào trang -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            $('#colorTableBody tr').each(function() {
                var colorName = $(this).find('td').eq(1).text().toLowerCase(); // Tên màu sắc nằm ở cột thứ 2 (index 1)
                if (colorName.indexOf(searchValue) > -1) {
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
unset($_SESSION["color_error"]);
?>
