<?php

include "../../db/Connect.php";

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền cập nhật voucher)

// Lấy vid từ tham số trên URL
$voucherId = $_GET['vid'];

// Nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy giá trị từ form
    $newName = $_POST['new_name'];
    $newType = $_POST['new_type'];
    $newDesc = $_POST['new_desc'];
    $newAmount = $_POST['new_amount'];
    $newStart = $_POST['new_start'];
    $newEnd = $_POST['new_end'];
    $newStatus = $_POST['new_status'];
    $newPercent = $_POST['new_percent'];
    $newCondition = $_POST['new_condition'];

    // Kiểm tra điều kiện số lượng và phần trăm giảm giá
        // Cập nhật thông tin trong bảng voucher
        $updateQuery = "UPDATE voucher SET vname = ?, vtype = ?, vdesc = ?, vamount = ?, vstart = ?, vend = ?, vpercent = ?, vstatus = ?, vremaining = ?, vcondition = ? WHERE vid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssssssi", $newName, $newType, $newDesc, $newAmount, $newStart, $newEnd, $newPercent, $newStatus,$newAmount, $newCondition, $voucherId);


        // Kiểm tra xem prepare có thành công không
        if (!$stmt) {
            die('Error preparing statement for update voucher: ' . $conn->error);
        }

        // Thực hiện execute
        if (!$stmt->execute()) {
            die('Error executing statement for update voucher: ' . $stmt->error);
        }

        $stmt->close();

        // Chuyển hướng về trang quản lý voucher
        header("Location: index.php?manage=voucher");
        exit();
    } 


// Lấy thông tin voucher từ bảng voucher
$query = "SELECT * FROM voucher WHERE vid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $voucherId);
$stmt->execute();
$result = $stmt->get_result();
$voucherInfo = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cập nhật thông tin voucher</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
  /* Thêm CSS để tạo hiệu ứng tooltip */
  #error-tooltip {
    position: absolute;
    background-color: #f44336;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    display: none;
    z-index: 1;
  }
  </style>
</head>

<body style="margin-left:100px;background-color: #f1efef">
  <h2 style="margin-top:10px">Cập nhật thông tin voucher</h2>
  <form class="row g-3" onsubmit="return validateForm()" method="POST" enctype="multipart/form-data"
    style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;">
    <!-- Thêm các trường nhập thông tin cho voucher -->
    <div class="col-md-12">
      <label for="new_name" class="form-label">Tên Voucher:</label>
      <input type="text" class="form-control" id="new_name" name="new_name" value="<?php echo $voucherInfo['vname']; ?>"
        required>
    </div>
    <div class="col-md-12">
      <label for="new_type" class="form-label">Loại voucher:</label>
      <input type="text" class="form-control" id="new_type" name="new_type" value="<?php echo $voucherInfo['vtype']; ?>"
        required>
    </div>
    <div class="mb-12">
      <label for="new_desc" class="form-label">Mô tả:</label>
      <textarea class="form-control" name="new_desc" id="new_desc"
        rows="3"><?php echo $voucherInfo['vdesc']; ?></textarea>
    </div>
    <div class="col-md-4">
      <label for="new_amount" class="form-label">Số lượng:</label>
      <input type="number" name="new_amount" class="form-control" value="<?php echo $voucherInfo['vamount']; ?>"
        required max="1000" min="0">
    </div>
    <div class="col-md-4">
      <label for="new_start" class="form-label">Ngày bắt đầu:</label>
      <input type="date" class="form-control" id="new_start" name="new_start"
        value="<?php echo $voucherInfo['vstart']; ?>" required>
    </div>
    <div class="col-md-4">
      <label for="new_end" class="form-label">Ngày kết thúc:</label>
      <input type="date" class="form-control" id="new_end" name="new_end" value="<?php echo $voucherInfo['vend']; ?>"
        required>
    </div>
    <div class="col-md-4">
      <label for="new_status" class="form-label">Trạng thái</label>
      <select class="form-select" id="new_status" name="new_status" required>
        <option value="Active" <?php echo ($voucherInfo['vstatus'] === 'Active') ? 'selected' : ''; ?>>Active</option>
        <option value="Inactive" <?php echo ($voucherInfo['vstatus'] === 'Inactive') ? 'selected' : ''; ?>>Inactive
        </option>
      </select>
    </div>
    <div class="col-md-4">
      <label for="new_percent" class="form-label">Phần trăm giảm giá:</label>
      <input type="number" name="new_percent" value="<?php echo $voucherInfo['vpercent']; ?>" class="form-control"
        required max="100" min="0">
    </div>
    <div class="col-md-4">
      <label for="new_condition" class="form-label">Điều kiện:</label>
      <input type="number" name="new_condition" value="<?php echo $voucherInfo['vcondition']; ?>" class="form-control"
        required min="0">
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Thêm mới voucher</button>
    </div>
  </form>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
?>