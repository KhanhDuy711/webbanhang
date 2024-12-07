<?php

include("../../db/MySQLConnect.php");

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)
if (!isset($_SESSION["product_edit_error"])) {
    $_SESSION["product_edit_error"] = "";
}

if (!isset($_SESSION["product_add_error"])) {
    $_SESSION["product_add_error"] = "";
}

$query = "SELECT * FROM product";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/Product-Management.css">
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <div class="container">
        <h2 style="margin-top:10px">Quản lý sản phẩm</h2>
        <font color=red><?php echo $_SESSION["product_edit_error"]; ?></font><br>
        <font color=red><?php echo $_SESSION["product_add_error"]; ?></font><br>
        
        <!-- Ô tìm kiếm sản phẩm -->
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Tìm kiếm sản phẩm theo tên...">
        </div>

        <button type="button" class="btn btn-primary"><a class="button-Add" href="index.php?manage=Product_add">Thêm sản phẩm</a></button>
        <button type="button" class="btn btn-secondary" onclick="window.open('print_product_list.php', '_blank')">In danh sách</button>

        <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
            <thead>
                <tr>
                    <th scope="col">Mã sản phẩm</th>
                    <th scope="col">Tên sản phẩm</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Giá niêm yết</th>
                    <th scope="col">Giá bán</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tình trạng</th>
                    <th scope="col">Chỉnh sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['pid']}</td>";
                    echo "<td>{$row['pname']}</td>";
                    echo "<td>{$row['pdesc']}</td>";
                    echo "<td>{$row['poriginalprice']}</td>";
                    echo "<td>{$row['psellprice']}</td>";
                    echo "<td>{$row['pquantity']}</td>";
                    echo '<td><img src="data:image/jpeg;base64,' . $row['pimage'] . '" alt="Product Image" style="width: 100px; height: auto;"></td>';
                    echo "<td>Khả dụng</td>";
                    echo "<td><button type='button' class='btn btn-warning'><a class='button-edit' href='index.php?manage=Product_edit&pid={$row['pid']}'>Chỉnh sửa</a></button></td>";
                    echo '<td><button type="button" class="btn btn-danger"><a class="button-delete" onclick="return confirm(\'Bạn có chắc muốn xóa sản phẩm: ' . $row["pname"] . ' không?\')" href="Product_delete.php?pid=' . $row["pid"] . '">Xóa</a></button></td>';
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
            $('#productTableBody tr').each(function() {
                var productName = $(this).find('td').eq(1).text().toLowerCase(); // Tên sản phẩm nằm ở cột thứ 2 (index 1)
                if (productName.indexOf(searchValue) > -1) {
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
unset($_SESSION["product_add_error"]);
unset($_SESSION["product_edit_error"]);
// Đóng kết nối
$connect->close();
?>
