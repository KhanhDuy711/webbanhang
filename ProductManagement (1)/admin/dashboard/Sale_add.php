<?php

if (!isset($_SESSION["sale_error"])) {
    $_SESSION["sale_error"] = "";
}
include "../../db/connect.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm chương trình khuyến mãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Thêm chương trình khuyến mãi</h2>
    
    <form class="row g-3"  enctype="multipart/form-data" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;" method="POST" action="Sale_add_action.php">
        <div class="col-md-12">
            <label for="salename" class="form-label">Tên chương trình:</label>
            <input type="text" class="form-control" id="colorname" name="txtsalename" required>
        </div>
        <div class="col-md-12">
            <label for="salename" class="form-label">Phần trăm giảm giá:</label>
            <input type="number" class="form-control" id="colorname" name="txtsalepercent" min="0" max="100" style="width:500px;" required>
        </div>
        <div class="col-md-4">
            <label for="voucher_start" class="form-label">Ngày bắt đầu:</label>
            <input type="date" class="form-control" id="voucher_start" name="txtsalestart" required>
        </div>
        <div class="col-md-4">
            <label for="voucher_end" class="form-label">Ngày kết thúc:</label>
            <input type="date" class="form-control" id="voucher_end" name="txtsaleend" required>
        </div>
    
        <font color=red><?php echo $_SESSION["sale_error"]; ?></font><br>

        <div class="col-12">
            <button class="btn btn-primary" type="reset">Reset</button>
            <button class="btn btn-primary" type="submit">Thêm chương trình</button>
        </div>
    </form>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
unset($_SESSION["sale_error"]);
?>