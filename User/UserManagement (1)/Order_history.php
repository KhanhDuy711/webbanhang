<?php
session_start();
include "Connect.php"; // Include your database connection file

// Check if the customer is logged in
if (isset($_SESSION["cid"])) {
    // The customer is logged in
    $customer_id = $_SESSION["cid"];
    $customer_name = $_SESSION["cname"];
    // You can use $customer_id and $customer_name as needed
} else {
    // The customer is not logged in
    // You may want to redirect them to the login page or handle it accordingly
    header("Location: login.php");
    exit();
}

// Get customer ID from the session
$customerID = $_SESSION['cid'];

// Fetch order history for the customer, excluding orders with status "Hủy đơn"
$query = "SELECT a.*, b.pname, b.pid FROM receipt a
            LEFT JOIN product_receipt c ON a.rid = c.rid
            JOIN product b ON c.pid = b.pid
            WHERE cid = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $customerID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Order History</title>
    <style>
        /* Your CSS styles go here */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
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
<header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("header_da_2.php"); ?>
    </div>
  </header>
  <?php include_once("navigation_da_2.php"); ?>
    <h2>Order History</h2>
    <?php
    // Check if there are orders    
    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Order Name</th><th>Order Date</th><th>Total Amount</th><th>Action</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['pname'] . '</td>';
            echo '<td>' . $row['rdate'] . '</td>';
            echo '<td>' . $row['rmoney'] . '</td>';
        
            // Check order status (case-insensitive) and display the appropriate action
            if (strcasecmp($row['rstatus'], 'Đang xử lý') === 0) {
                echo '<td><a href="cancel_order.php?rid=' . $row['rid'] . '">Cancel Order</a></td>';
            } elseif (strcasecmp($row['rstatus'], 'Hoàn thành') === 0) {
                echo '<td><a href="comment.php?rid=' . $row['rid'] . '&pid=' . $row['pid'] . '">Feedback</a></td>';
            } 
            else {
                echo '<td>Cannot Perform Action</td>';
            }
        
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No orders found.';
    }
    ?>
</body>
<!-- Footer -->
<div class="container-fluid index" id="index">
    <footer>
        <?php include_once("footer.php");  ?>
    </footer>
  </div>
</html>
