<?php
session_start();
include "Connect.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedbackID = $_POST['fid'];
    $productID = $_POST['pid'];
    $receiptID = $_POST['rid'];
    // Delete the feedback
    $query = "DELETE FROM feedback WHERE fid = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $feedbackID);

    if (!$stmt->execute()) {
        die('Error executing statement: ' . $stmt->error);
    }

    // Redirect back to the product feedback page
    header("Location: comment.php?pid=" . $productID . "&rid=" . $receiptID);
    exit();
    // Close your database connections and statements
    $stmt->close();
    $conn->close();
}
?>
