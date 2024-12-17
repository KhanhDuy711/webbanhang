<?php
session_start();
include "connect.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["cid"])) {
        $customer_id = $_SESSION["cid"];
    } else {
        // Handle the case when the customer is not logged in
        header("Location: login.php"); // Redirect to the login page
        exit();
    }
    $latestReceiptId = "";

// After processing the order and inserting data into the database
$_SESSION['latest_receipt_id'] = $latestReceiptId; // Assuming you have the receipt ID


    if (!isset($_SESSION["error"])) {
        $_SESSION["error"] = "";
    }

    // Check if $_SESSION["cart_item"] is set and is an array
    if (isset($_SESSION["cart_item"]) && is_array($_SESSION["cart_item"])) {
        foreach ($_SESSION["cart_item"] as $item) {
            $check = "SELECT pquantity FROM product WHERE pid = " . $item["pid"];
            $KQ = $conn->query($check);

            // Debug statement: Print the query and check for errors
            if (!$KQ) {
                echo "Query error: " . $conn->error;
                exit();
            }

            $dong = $KQ->fetch_assoc();
            if ($dong["pquantity"] < $item["pquantity"]) {
                $_SESSION["error"] = "Số lượng sản phẩm \"" . $item["pname"] . "\" đang vượt quá số hàng trong kho!!!";
                header("Location: cart.php");
                exit(); // Stop the script execution after redirection
            }
        }
    } else {
        // Handle the case where cart_item is not set or not an array
        $_SESSION["error"] = "Giỏ hàng trống hoặc có lỗi.";
        header("Location: cart.php");
        exit();
    }

    // Get form data
    $address = $_POST["address"];
    $paymentMethod = $_POST["payment_method"];
    $vid = $_POST["voucher"];
    $total_price = $_POST["total_price"];
    // Get the current date and time
    $currentDate = date("Y-m-d H:i:s");
    // Insert into the receipt table
    if (empty($vid)) {
        $insertReceiptQuery = "INSERT INTO receipt (rdate, cid, rmoney, rstatus,  vid, rpaymentmethod, rdestination) VALUES ('$currentDate', $customer_id, $total_price, 'Đang Xử Lý', NULL, '$paymentMethod', '$address')";
    } else {
        $insertReceiptQuery = "INSERT INTO receipt (rdate, cid, rmoney, rstatus, vid, rpaymentmethod, rdestination) VALUES ('$currentDate', $customer_id, $total_price, 'Đang Xử Lý', '$vid', '$paymentMethod', '$address')";
    }

    // Debug statement: Print the query and check for errors
    echo "Query: $insertReceiptQuery<br>";

    if ($conn->query($insertReceiptQuery) === TRUE) {
        echo "New record inserted successfully<br>";
    } else {
        echo "Error: " . $insertReceiptQuery . "<br>" . $conn->error;
        exit();
    }

    // Update voucher status if used
    if (!empty($vid)) {
        $updateVoucherQuery = "UPDATE voucher SET vremaining = vremaining -1 WHERE vid = '$vid'";
        $conn->query($updateVoucherQuery);
    }
    
    //Lấy rid 
    $sql="SELECT rid FROM `receipt` order by rid desc limit 1";// lấy rid lớn nhất
    $result = $conn->query($sql) or die($conn->connect_error);
    $row = $result->fetch_assoc();
    //insert into product_receipt;
    foreach($_SESSION["cart_item"] as $item){
        $item_price = $item["pquantity"]*$item["pprice"];
        $sql1 = "insert into product_receipt(rid,pid,size,color,quantity,itemprice) values(".$row["rid"].",".$item["pid"].",'".$item["size"]."','".$item["color"]."',".$item["pquantity"].",".$item_price.")";
        echo $sql1;
        $conn->query($sql1) or die($conn->connect_error);
        
        $updateSold = "UPDATE product SET psold = psold + ".$item["pquantity"]." WHERE pid = " . $item["pid"];
        $resultSold = $conn->query($updateSold) or die($conn->error);

        $updateQuantity = "UPDATE product SET pquantity = pquantity - ".$item["pquantity"]." WHERE pid = " . $item["pid"];
        $conn->query($updateQuantity);
    }
    unset($_SESSION["cart_item"]);

    // Additional logic or redirection if needed
    header("Location: success_page.php"); // Redirect to a success page
    exit();
} else {
    // Handle cases where the form is not submitted properly
    header("Location: error_page.php"); // Redirect to an error page
    exit();
}
?>