<?php
include "Connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['vid'])) {
    $voucherId = $_GET['vid'];

    // Thực hiện truy vấn xóa
    $deleteQuery = "DELETE FROM voucher WHERE vid = ?";
    $stmt = $conn->prepare($deleteQuery);
    
    if ($stmt) {
        $stmt->bind_param("i", $voucherId);
        if ($stmt->execute()) {
            // Successful deletion
            $stmt->close();
        } else {
            // Error during execution
            echo "Error: " . $stmt->error;
        }
    }
    

    // Chuyển hướng về trang quản lý voucher
    header("Location: manage_vouchers.php");
    exit();
}
?>
