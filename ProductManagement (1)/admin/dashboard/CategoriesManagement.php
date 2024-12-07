<?php

if (!isset($_SESSION["cate_error"])){
    $_SESSION["cate_error"]="";
}
include("../../db/MySQLConnect.php");

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)

$query = "SELECT * FROM categories WHERE catestatus=1";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
        .button-edit, .button-delete, .button-Add {
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body style="margin-left:100px;background-color: #f1efef">
    <div class="container">
        <h2 style="margin-top:10px">Quản lý danh mục</h2>
        <center>
            <font color=red><?php echo $_SESSION["cate_error"]; ?></font>
        </center>
        
        <!-- Ô tìm kiếm danh mục -->
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Tìm kiếm danh mục theo tên...">
        </div>

        <button type="button" class="btn btn-primary">
            <a class="button-Add" href="index.php?manage=Categories_add">Thêm mới danh mục</a>
        </button>
        <button type="button" class="btn btn-secondary">
            <a class="button-Print" href="Categories_print.php" target="_blank">In danh mục</a>
        </button>

        <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
            <thead>
                <tr>
                    <th scope="col">Mã danh mục</th>
                    <th scope="col">Tên danh mục</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['cateid']}</td>";
                    echo "<td>{$row['catename']}</td>";
                    echo "<td>{$row['catedesc']}</td>";
                    echo "<td><button type='button' class='btn btn-warning'><a href='index.php?manage=Categories_edit&cateid={$row['cateid']}'>Sửa</a></button></td>";
                    echo "<td><button type='button' class='btn btn-danger'><a onclick=\"return confirm('Bạn có chắc muốn xóa danh mục {$row['catename']} không?')\" href='action/Categories_delete.php?cateid={$row['cateid']}'>Xóa</a></button></td>";
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
            $('#categoryTableBody tr').each(function() {
                var categoryName = $(this).find('td').eq(1).text().toLowerCase(); 
                if (categoryName.indexOf(searchValue) > -1) {
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
unset($_SESSION["cate_error"]);
?>
