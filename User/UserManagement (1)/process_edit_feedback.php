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
    if (!empty($_FILES["fmedia"]["name"])) {
        // File upload handling
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["fmedia"]["name"]);

        // Check file type (you can adjust the allowed file types)
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4'];
        $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedFileTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, MP4 files are allowed.";
            exit();
        }

        // Check file size
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($_FILES["fmedia"]["size"] > $maxFileSize) {
            echo "Sorry, your file is too large. Maximum file size is 5 MB.";
            exit();
        }

        // Move the uploaded file to the "uploads" directory
        if (is_uploaded_file($_FILES["fmedia"]["tmp_name"]) && move_uploaded_file($_FILES["fmedia"]["tmp_name"], $targetFile)) {
            // Success: File was successfully uploaded
            $media = $targetFile;
        } else {
            // Error: File upload failed
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        // No new file uploaded, use the existing media path
        $media = $existingMedia;
    }

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
