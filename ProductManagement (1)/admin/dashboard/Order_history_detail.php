<?php
session_start();
include "../../db/Connect.php";

// Check if the customer is logged in
if (isset($_SESSION["admin_login"])) {
    // The customer is logged in
   
    $customer_name = $_SESSION["admin_name"];
    
    // You can use $customer_id and $customer_name as needed
} else {
    // The customer is not logged in
    // You may want to redirect them to the login page or handle it accordingly
    header("Location: login.php");
    exit();
}

$rid_from_url = $_GET['rid'];
// Fetch order history for the customer, excluding orders with status "Hủy đơn"
$query = "SELECT a.*, b.*, c.*, cs.* FROM receipt a
            JOIN product_receipt c ON a.rid = c.rid
            JOIN product b ON c.pid = b.pid
            JOIN  customer cs ON a.cid = cs.cid
            WHERE a.rstatus != 'Hủy đơn' AND c.rid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $rid_from_url);  // Use "s" for string type
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order History</title>
  <style>
  /* Your CSS styles go here */
  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    padding: 8px;
    text-align: left;
    border: 1px solid #ddd;
  }

  th {
    background-color: #f2f2f2;
  }
  </style>
</head>

<body>
  <h2>Chi tiết đơn hàng</h2>
  <?php
    // Check if there are orders    
    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Mã đơn hàng</th><th>Mã sản phẩm</th><th>Tên sản phẩm</th><th>Ngày đặt hàng</th><th>Giá sản phẩm</th><th>Số lượng</th><th>Mã khách hàng</th><th>Tên khách hàng</th><th>Địa chỉ giao hàng</th><th>Tổng tiền cần thanh toán</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['rid'] . '</td>';
            echo '<td>' . $row['pid'] . '</td>';
            echo '<td>' . $row['pname'] . '</td>';
            echo '<td>' . $row['rdate'] . '</td>';
            echo '<td>' . number_format($row['itemprice']/$row['quantity']) . ' VND /1sp' . '</td>';
            echo '<td>' . number_format($row['quantity']) . '</td>';
            echo '<td>' . $row['cid'] . '</td>';
            echo '<td>' . $row['cname'] . '</td>';
            echo '<td>' . $row['rdestination'] . '</td>';
            echo '<td>' . number_format($row['rmoney']) . ' VND'. '</td>';
            // Check order status (case-insensitive) and display the appropriate action
           

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No orders found.';
    }
    ?>
</body>

</html>