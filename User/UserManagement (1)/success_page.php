<?php
session_start();
include "Connect.php"; // Include your database connection file

if (isset($_SESSION["cid"])) {
    $customer_id = $_SESSION["cid"];
} else {
    // Handle the case when the customer is not logged in
    header("Location:UserManagement (1)/login.php"); // Redirect to the login page
    exit();
}

// Retrieve the latest receipt for the logged-in customer, excluding orders with status "Hủy đơn"
$query = "SELECT a.*, b.pname, b.pid FROM receipt a
            JOIN product_receipt c ON a.rid = c.rid
            JOIN product b ON c.pid = b.pid
            WHERE cid = ? AND a.rstatus != 'Hủy đơn'
            ORDER BY a.rid DESC LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize $latestReceipt as an empty array
$latestReceipt = [];

// Check if there is a result from the query
if ($result->num_rows > 0) {
    $latestReceipt = $result->fetch_assoc();
} else {
    // Handle the case when no receipt is found
    // Redirect or display an error message
    header("Location: error_page.php");
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../index.css">
    <title>Success Page</title>
    <!-- Your CSS Styles if needed -->
</head>
<body>
    <?php include 'header_da_2.php' ?> 
    <?php include 'navigation_da_2.php' ?>
    <br><br><br> 
    <div class=container-success-page>
        <h1>Đơn Hàng Đã Đặt Thành Công</h1>

        <h2>Thông Tin Hóa Đơn</h2>
        <?php if (!empty($latestReceipt)): ?>
            <p><strong>Ngày đặt hàng:</strong> <?php echo $latestReceipt['rdate']; ?></p>
            <p><strong>Địa chỉ nhận hàng:</strong> <?php echo $latestReceipt['rdestination']; ?></p>
            <p><strong>Số tiền thanh toán:</strong> <?php echo $latestReceipt['rmoney']; ?></p>
            <p><strong>Phương thức thanh toán:</strong> <?php echo $latestReceipt['rpaymentmethod']; ?></p>

            <?php if (!empty($latestReceipt['vid'])): ?>
                <p><strong>Mã Voucher:</strong> <?php echo $latestReceipt['vid']; ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>No receipt found.</p>
        <?php endif; ?>
    </div>
    <!-- Additional receipt details if needed -->
    <!-- Your HTML and CSS for additional content -->
    <a class="button-back" href="../index.php">Quay Lại Trang Chủ</a>
    <br><br><br>
    <img src="../images/footer.jpg" class="footer-img">

</body>
<style>
        .container-success-page {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }

        h1, h2 {
            color: #333;
        }

        p {
            margin: 10px 0;
            color: #555;
        }

        strong {
            font-weight: bold;
        }

        .button-back {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
            margin-left: 630px;
        }

        .button-back:hover {
            background-color: #45a049;
        }
        .footer-img {
            max-width: 100%;
        }
    </style>
</html>
