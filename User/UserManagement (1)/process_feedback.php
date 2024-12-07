<?php
session_start();
include "Connect.php"; // Include your database connection file

// Check if the customer is logged in
if (!isset($_SESSION["cid"])) {
    // Redirect them to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $rid = $_POST['rid'];
    $productID = $_POST['pid'];
    $fcontent = $_POST['fcontent'];
    $fstar = $_POST['fstar'];
    $feedbackDate = date("Y-m-d H:i:s");

    //lấy thông tin id từ pid
    $queryFeedback = "SELECT id FROM product_receipt a
                JOIN receipt b ON a.rid = b.rid
                WHERE a.pid = ? AND b.cid = ?";
    $stmtFeedback = $conn->prepare($queryFeedback);

    // Execute the query to get the id
    $stmtFeedback->bind_param("ss", $productID, $_SESSION['cid']);
    $stmtFeedback->execute();
    $stmtFeedback->bind_result($productReceiptId);
    $stmtFeedback->fetch();
    $stmtFeedback->close();

    // Check if a file is uploaded
    if (!empty($_FILES["fmedia"]["name"])) {
        // File upload handling
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["fmedia"]["name"]); // Adjusted filename

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
        // No file uploaded
        $media = 'no_media';
    }

    // Insert feedback into the feedback table
    $insertQuery = "INSERT INTO feedback (fcontent, fstar, fmedia, fdate, id) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);

    if (!$stmtInsert) {
        die('Error preparing statement for feedback insertion: ' . $conn->error);
    }

    // Adjust the data types in bind_param accordingly
    $stmtInsert->bind_param("sssss", $fcontent, $fstar, $media, $feedbackDate, $productReceiptId);

    if (!$stmtInsert->execute()) {
        die('Error executing statement for feedback insertion: ' . $stmtInsert->error);
    }

    // Get the id of the inserted feedback
    $id = $stmtInsert->insert_id;

    // Insert id into the product_receipt table
    $insertFidQuery = "UPDATE product_receipt SET fid = ? WHERE rid = ? AND pid = ?";
    $stmtInsertFid = $conn->prepare($insertFidQuery);

    if (!$stmtInsertFid) {
        die('Error preparing statement for fid insertion in product_receipt: ' . $conn->error);
    }

    $stmtInsertFid->bind_param("sss", $id, $rid, $productID);

    if (!$stmtInsertFid->execute()) {
        die('Error executing statement for fid insertion in product_receipt: ' . $stmtInsertFid->error);
    }

    // Redirect back to the product feedback page
    header("Location: comment.php?pid=" . $productID . '&rid=' . $rid);
    exit();
}

// Close your database connections and statements
$stmtInsert->close();
$stmtInsertFid->close();
$conn->close();
?>
