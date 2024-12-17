<?php
session_start();
include "Connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedbackID = $_POST['fid'];
    $productID = $_POST['pid'];
    $receiptID = $_POST['rid'];
    $newContent = $_POST['fcontent'];
    $newStarRating = $_POST['fstar'];
    $existingMedia = $_POST['fmedia'];

    $media = '';

    // Check if a new media file is uploaded
    

    // Update feedback content, star rating, and media in the database
    $updateQuery = "UPDATE feedback SET fcontent = ?, fstar = ?, fmedia = ? WHERE fid = ?";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param("sssi", $newContent, $newStarRating, $media, $feedbackID);

    if ($stmtUpdate->execute()) {
        // Redirect or perform other actions after successful update
        header("Location: comment.php?pid=" . $productID . '&rid=' . $rid);
        exit();
    } else {
        echo 'Error updating feedback: ' . $stmtUpdate->error;
    }

    // Close statement
    $stmtUpdate->close();
}

// Close database connection
$conn->close();
?>
