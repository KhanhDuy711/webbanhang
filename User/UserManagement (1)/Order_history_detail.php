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
$rid_from_url = $_GET['rid'];
// Fetch order history for the customer, excluding orders with status "Hủy đơn"
$query = "SELECT a.*, b.*, c.quantity FROM receipt a
            JOIN product_receipt c ON a.rid = c.rid
            JOIN product b ON c.pid = b.pid
            WHERE cid = ? AND a.rstatus != 'Hủy đơn' and c.rid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $customerID, $rid_from_url);
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
    <h2>Order product</h2>
    <?php
    // Check if there are orders    
    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Product name</th><th>Order date</th><th>Sell price</th><th>Quantity</th><th>Action</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['pname'] . '</td>';
            echo '<td>' . $row['rdate'] . '</td>';
            echo '<td>' . $row['psellprice'] . '</td>';
            echo '<td>' . $row['quantity'] . '</td>';
            // Check order status (case-insensitive) and display the appropriate action
           if (strcasecmp($row['rstatus'], 'Hoàn thành') === 0) {
                echo '<td><a href="comment.php?rid=' . $row['rid'] . '&pid=' . $row['pid'] . '">Feedback</a></td>';
            } else {
                echo '<td>Cannot feedback</td>';
            }

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No orders found.';
    }
    ?>
</body>
</html>
