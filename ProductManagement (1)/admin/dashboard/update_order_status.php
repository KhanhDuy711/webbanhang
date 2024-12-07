<?php

include "../../db/Connect.php";

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý đơn hàng)


// Lấy rid từ tham số trên URL
$receiptId = $_GET['rid'];

// Nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy giá trị trạng thái mới từ form
    $newStatus = $_POST['new_status'];

    // Cập nhật trạng thái trong bảng receipt
    $updateQuery = "UPDATE receipt SET rstatus = ? WHERE rid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newStatus, $receiptId);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng về trang quản lý đơn hàng
    header("Location: index.php?manage=order");
    exit();
}

// Lấy thông tin đơn hàng từ bảng receipt
$query = "SELECT rdate, rmoney, rstatus, rpaymentmethod, rdestination FROM receipt WHERE rid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $receiptId);
$stmt->execute();
$result = $stmt->get_result();
$orderInfo = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        * {
            font-size: 20px;
            font-family: sans-serif;
            font-weight: 400;
        }
    </style>
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <h2>Cập nhật trạng thái đơn hàng</h2>
    <hr>
    <p>Thông tin đơn hàng:</p>
    <ul>
        <li>Ngày đặt hàng: <?php echo $orderInfo['rdate']; ?></li>
        <li>Tổng tiền: <?php echo $orderInfo['rmoney']; ?></li>
        <li>Trạng thái hiện tại: <?php echo $orderInfo['rstatus']; ?></li>
        <li>Phương thức thanh toán: <?php echo $orderInfo['rpaymentmethod']; ?></li>
        <li>Nơi nhận: <?php echo $orderInfo['rdestination']; ?></li>
    </ul>
    <form method="post" action="">
        <div class="col-md-4">
           

                <?php
                $rstatus = $orderInfo['rstatus'];

                $options = array("Đang xử lý", "Đang giao hàng", "Hoàn thành", "Hủy đơn");
                ?>

                <h1><?php echo "$rstatus" ?></h1>
                <label for="new_status" class="form-label">Cập nhật trạng thái mới:</label>
                <select class="form-select" id="new_status" name="new_status" required>
                    <?php
                    // Mảng chứa các giá trị cho các tùy chọn
                    $options = array("Đang xử lý", "Đang giao hàng", "Hoàn thành", "Hủy đơn");

                    // Lặp qua từng tùy chọn và tạo các tùy chọn trong thẻ select
                    foreach ($options as $option) {
                        // Kiểm tra nếu giá trị từ CSDL trùng với giá trị tùy chọn
                        $selected = ($rstatus == $option) ? 'selected' : '';

                        // Tạo tùy chọn với thuộc tính selected nếu giá trị từ CSDL trùng với giá trị tùy chọn
                        echo '  <option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                    }
                    ?>
                </select>

        </div>
        <br>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Cập nhật trạng thái</button>
        </div>
    </form>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
?>