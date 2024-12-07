<?php

// Start the session and check for any voucher-related errors
if (!isset($_SESSION["voucher_error"])) {
    $_SESSION["voucher_error"] = "";
}

// Include the database connection file
include("../../db/MySQLConnect.php");

// Query to fetch all vouchers from the database
$query = "SELECT * FROM voucher";
$result = $connect->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý voucher</title>
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
    <h2 style="margin-top:10px">Quản lý voucher</h2>
    <center>
        <font color="red"><?php echo $_SESSION["voucher_error"]; ?></font>
    </center>

    <!-- Ô tìm kiếm voucher -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Tìm kiếm voucher theo tên...">
    </div>

    <!-- Button to add a new voucher -->
    <button type="button" class="btn btn-primary">
        <a class="button-Add" href="index.php?manage=Voucher_add">Thêm voucher</a>
    </button>
    <button type="button" class="btn btn-secondary">
        <a class="button-Print" href="Voucher_print.php" target="_blank">In danh sách voucher</a>
    </button>

    <!-- Table to display all vouchers -->
    <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
        <thead>
            <tr>
                <th scope="col">ID Voucher</th>
                <th scope="col">Tên Voucher</th>
                <th scope="col">Loại Voucher</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Phần trăm giảm giá</th>
                <th scope="col">Số lượng</th>
                <th scope="col">Ngày bắt đầu</th>
                <th scope="col">Ngày kết thúc</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Số lượng còn lại</th>
                <th scope="col">Điều kiện</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody id="voucherTableBody">
            <!-- Loop through each voucher and display it in a table row -->
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['vid']); ?></td>
                    <td><?php echo htmlspecialchars($row['vname']); ?></td>
                    <td><?php echo htmlspecialchars($row['vtype']); ?></td>
                    <td><?php echo htmlspecialchars($row['vdesc']); ?></td>
                    <td><?php echo htmlspecialchars($row['vpercent']); ?></td>
                    <td><?php echo htmlspecialchars($row['vamount']); ?></td>
                    <td><?php echo htmlspecialchars($row['vstart']); ?></td>
                    <td><?php echo htmlspecialchars($row['vend']); ?></td>
                    <td><?php echo htmlspecialchars($row['vstatus']); ?></td>
                    <td><?php echo htmlspecialchars($row['vremaining']); ?></td>
                    <td><?php echo htmlspecialchars($row['vcondition']); ?></td>
                    <td>
                        <button type='button' class='btn btn-warning'>
                            <a class='button-edit' href='index.php?manage=update_voucher_status&vid=<?php echo htmlspecialchars($row['vid']); ?>'>Cập nhật trạng thái</a>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Add jQuery to the page -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            $('#voucherTableBody tr').each(function() {
                var voucherName = $(this).find('td').eq(1).text().toLowerCase(); // Tên voucher nằm ở cột thứ 2 (index 1)
                if (voucherName.indexOf(searchValue) > -1) {
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
// Close the database connection and clear any session error messages
$connect->close();
unset($_SESSION["voucher_error"]);
?>
