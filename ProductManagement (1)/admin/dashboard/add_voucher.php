<?php

include("../../db/MySQLConnect.php");
// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)

// Nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $voucherName = $_POST['voucher_name'];
    $voucherType = $_POST['voucher_type'];
    $voucherDesc = $_POST['voucher_desc'];
    $voucherAmount = $_POST['voucher_amount'];
    $voucherStart = $_POST['voucher_start'];
    $voucherEnd = $_POST['voucher_end'];
    $voucherStatus = isset($_POST['voucher_status']) ? $_POST['voucher_status'] : '';
    $voucherRemaining = $voucherAmount;
    $voucherPercent = isset($_POST['voucher_percent']) ? $_POST['voucher_percent'] : 0;
    $voucherCodition = isset($_POST['voucher_condition']) ? $_POST['voucher_condition'] : 0;
    // Kiểm tra điều kiện số lượng và phần trăm giảm giá
    if ($voucherAmount <= 1000 && $voucherPercent <= 100) {
        // Thực hiện thêm voucher vào bảng voucher
        $insertQuery = "INSERT INTO voucher (vname, vtype, vdesc, vamount, vstart, vend, vstatus, vremaining, vpercent, vcondition) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($insertQuery);
        $stmt->bind_param("ssssssssss", $voucherName, $voucherType, $voucherDesc, $voucherAmount, $voucherStart, $voucherEnd, $voucherStatus, $voucherRemaining, $voucherPercent, $voucherCodition);

        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            die('Error preparing statement for adding voucher: ' . $connect->error);
        }

        // Thực hiện execute
        if (!$stmt->execute()) {
            die('Error executing statement for adding voucher: ' . $stmt->error);
        }

        // Đóng statement
        $stmt->close();

        // Chuyển hướng về trang quản lý voucher
        header("Location: index.php?manage=voucher");
        exit();
    } 
    else {
        echo 'Số lượng và phần trăm giảm giá phải nhỏ hơn hoặc bằng 1000 và 100, respectively.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm voucher mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body style="margin-left:100px;background-color: #f1efef" >
    <h2 style="margin-top:10px">Thêm voucher mới</h2>
    <form class="row g-3"  method="POST" enctype="multipart/form-data" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;">
        <!-- Thêm các trường nhập thông tin cho voucher -->
        <div class="col-md-12">
            <label for="voucher_name" class="form-label">Tên Voucher:</label>
            <input type="text" class="form-control" id="voucher_name" name="voucher_name" placeholder="Sale...." required>
        </div>
        <div class="col-md-12">
            <label for="voucher_type" class="form-label">Loại voucher:</label>
            <input type="text" class="form-control" id="voucher_type" name="voucher_type" placeholder="Sale...." required>
        </div>
        <div class="mb-12">
            <label for="voucher_desc" class="form-label">Mô tả:</label>
            <textarea class="form-control" name="voucher_desc" id="voucher_desc" rows="3" placeholder="Sale..."></textarea>
        </div>
        <div class="col-md-4">
            <label for="voucher_amount" class="form-label">Số lượng:</label>
            <input type="number" name="voucher_amount" class="form-control"  required max="1000" min="0">
        </div>
        <div class="col-md-4">
            <label for="voucher_start" class="form-label">Ngày bắt đầu:</label>
            <input type="date" class="form-control" id="voucher_start" name="voucher_start" required>
        </div>
        <div class="col-md-4">
            <label for="voucher_end" class="form-label">Ngày kết thúc:</label>
            <input type="date" class="form-control" id="voucher_end" name="voucher_end" required>
        </div>
        <div class="col-md-4">
            <label for="voucher_status" class="form-label">Trạng thái</label>
            <select class="form-select" id="voucher_status" name="voucher_status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="voucher_percent" class="form-label">Phần trăm giảm giá:</label>
            <input type="number" name="voucher_percent" class="form-control"  required max="100" min="0">
        </div>
        <div class="col-md-4">
            <label for="voucher_condition" class="form-label">Điều kiện:</label>
            <input type="number" name="voucher_condition" class="form-control"  required  min="0">
        </div>
       
        <div class="col-12">
            <button class="btn btn-primary" type="reset">Reset</button>
            <button class="btn btn-primary" type="submit">Thêm mới voucher</button>
        </div>
    </form>
</body>
</html>

<?php
// Đóng kết nối
$connect->close();
?>
