<?php
session_start();
include "Connect.php";

// Check if the customer is logged in
if (!isset($_SESSION["cid"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["rid"])) {
    $customerID = $_SESSION['cid'];
    $receiptID = $_GET["rid"];

    // Check if the receipt belongs to the logged-in customer
    $checkQuery = "SELECT rid FROM receipt WHERE rid = ? AND cid = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $receiptID, $customerID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 1) {
        // Update the order status to "Cancelled"
        $updateQuery = "UPDATE receipt SET rstatus = 'Hủy đơn' WHERE rid = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $receiptID);
        
        if ($updateStmt->execute()) {
            // Set a success message
            $_SESSION['success_message'] = "Order has been cancelled successfully.";
        } else {
            $_SESSION['error_message'] = "Error cancelling the order.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid receipt ID or you don't have permission to cancel this order.";
    }

    $checkStmt->close();
    $updateStmt->close();

    // Redirect back to index.php with success or error message
    header("Location:CustomerDetail.php" . (isset($_SESSION['success_message']) ? "?success=" . urlencode($_SESSION['success_message']) : (isset($_SESSION['error_message']) ? "?error=" . urlencode($_SESSION['error_message']) : "")));
    exit();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
