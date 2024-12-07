<?php
session_start();
include "Connect.php"; // Include your database connection file

// Check if the customer is logged in
if (!isset($_SESSION["cid"])) {
    // Redirect them to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get customer ID from the session
$customerID = $_SESSION['cid'];

// Check if the product ID is provided in the query string
if (!isset($_GET['pid'])) {
    // Redirect to the home page or handle it accordingly
    header("Location: HomePage.php");
    exit();
}

// Get the product ID from the query string
$productID = $_GET['pid'];
$receiptID = $_GET['rid'];

// Fetch product details
$query = "SELECT * FROM product WHERE pid = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param("s", $productID);

if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error);
}

$result = $stmt->get_result();

// Fetch existing feedback for the product
$queryFeedback = "SELECT * FROM feedback a
                JOIN product_receipt c ON a.id = c.id
                JOIN receipt b ON c.rid = b.rid
                WHERE c.pid = ? AND b.cid = ?";
$stmtFeedback = $conn->prepare($queryFeedback);

if (!$stmtFeedback) {
    die('Error preparing statement for feedback: ' . $conn->error);
}

$stmtFeedback->bind_param("ss", $productID, $customerID);

if (!$stmtFeedback->execute()) {
    die('Error executing statement for feedback: ' . $stmtFeedback->error);
}

$resultFeedback = $stmtFeedback->get_result();

// Check if the user has already provided feedback
$hasProvidedFeedback = $resultFeedback->num_rows > 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/user.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Product Feedback</title>
</head>

<body>
<?php include_once("header_da_2.php")?>
    
<!-- Thanh navigation -->

    <?php require_once("navigation_da_2.php"); ?>
  

    
   

    <div class="feedback-container">
      
        <?php
        // hiển thị bình luận
        while ($feedback = $resultFeedback->fetch_assoc()) {
            if (!empty($feedback['fmedia'])) {
                $mediaPath = $feedback['fmedia'];
                echo '<div class="media-container">';
                if (pathinfo($mediaPath, PATHINFO_EXTENSION) == 'mp4') {
                    echo '<video width="420px" height="420px" controls>';
                    echo '<source src="' . $mediaPath . '" type="video/mp4">';
                    echo 'Your browser does not support the video tag.';
                    echo '</video>';
                } else {
                    echo '<img src="' . $mediaPath . '" alt="Feedback Media" width="400px" height="400px">';
                }
                echo '</div>';
            } else {
                echo '<p>No media available for this feedback.</p>';
            }
            ?>
            <div class="content-feedback">
            <h2 style="font-size: 40px;color:#008000">Product Feedback</h2>

            <?php
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                echo '<p style="font-size: 30px;font-weight: 400;"> Tên sản phẩm: <span style="font-size:25px; font-family:-webkit-body">' . $product['pname'] . '</span> </p>';
                echo '<p style="font-size: 30px;font-weight: 400;">Product Description: <span style="font-size:25px; font-family:-webkit-body">' . $product['pdesc'] . '</span></p>';
            } else {
                echo 'Product not found.';
                exit();
            }
            
            echo '<p style="font-size: 30px;font-weight: 400;">Comment: <span style="font-size:25px; font-family:-webkit-body">' . $feedback['fcontent'] . '</span></p>';
            echo '<p style="font-size: 30px;font-weight: 400;">Rating: <span style="font-size:25px; font-family:-webkit-body">' . $feedback['fstar'] . '</span> </p>';
            


       
                echo '<form method="post" action="edit_feedback.php?fid=' . urlencode($feedback['fid']) . '">';
                echo '<input type="hidden" name="pid" value="' . $productID . '">';
                echo '<input type="hidden" name="rid" value="' . $receiptID . '">';
                echo '<input type="hidden" name="fid" value="' . $feedback['fid'] . '">'; // Include fid here
                echo '<input type="hidden" name="fmedia" value="' . $feedback['fmedia'] . '">';
                echo '<button type="submit" class="buttonEdit">Edit Feedback</button>';
                echo '</form>';

                echo '<form method="post" action="delete_feedback.php">';
                echo '<input type="hidden" name="pid" value="' . $productID . '">';
                echo '<input type="hidden" name="rid" value="' . $receiptID . '">';
                echo '<input type="hidden" name="fid" value="' . $feedback['fid'] . '">';
                echo '<button type="submit" class="buttonEdit">Delete Feedback</button>';
                echo '</form>';
            
        }
        ?>
        </div>
    </div>
    
    <?php if (!$hasProvidedFeedback) { ?>
        <div class="feedback-form">
            <h3 style="margin-left: 165px;font-size: 40px;color:#008000 ">Add Feedback</h3>
            <form method="post" action="process_feedback.php" enctype="multipart/form-data">
                <input type="hidden" name="rid" value="<?php echo $receiptID; ?>">
                <input type="hidden" name="pid" value="<?php echo $productID; ?>">
                <textarea name="fcontent" rows="8" cols="70" required></textarea>
                <br>
                <label for="fstar" style="font-size: 25px;font-weight: 400;">Rating:</label>
                <input type="number" name="fstar" min="1" max="5" required>
                <br>
                <label for="fmedia" style="font-size: 25px;font-weight: 400;">Upload Image/Video:</label>
                <input type="file" name="fmedia">
                <br>
                <button type="submit" class="buttonEdit" style="margin-left: 100px;margin-top: 20px;">Submit Feedback</button>
            </form>
        </div>
    <?php } ?>

    <div class="container-fluid index" id="index">
    
  </div>


   <!-- Footer -->
   <div class="container-fluid index" id="index">
        <footer>
            <?php include_once("footer.php");  ?>
        </footer>
    </div>
</body>

</html>

<?php
$stmt->close();
$stmtFeedback->close();
$conn->close();
?>
<style>
  #navigation_bar {
    display: flex;
    position: sticky;
    top: 0;
    padding: 0;
    background: #333;
    z-index: 8;
    
}
  #nav {
    display: inline-block;
    padding-left: 0;
    margin-bottom: 0;
}

#nav>li {
    display: inline-block;

}

#nav li {
    position: relative;
}

#nav,
.subnav,
.ticket-list {
    list-style: none;
}

#nav>li>a {
    color: white;
    text-transform: uppercase;
}

#nav li a {
    display: inline-block;
    text-decoration: none;
    line-height: 46.5px;
    padding: 0 24px;
}

#nav>li:hover>a,
#nav .subnav li:hover {
    color: #000;
    background-color: #ccc;
}

#nav>#more-btn:hover .subnav {
    display: block;
}

#nav .nav-arrow-down {
    font-size: 12px;
}

#nav .subnav {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 160px;
    background-color: #fff;
    color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    

}




#nav .subnav a {
    color: black;
    padding: 0px 16px;
    line-height: 38.5px;


}

#header .search-button {
    float: right;
}

#header .search-button:hover {
    background-color: #f44336;
    cursor: pointer;

}

#header .search-icon {
    font-size: 16px;
    color: white;
    line-height: 46.5px;
    padding: 0 23px;
}
.icon {
  margin-left: 950px;
}

.feedback-container{
    display:flex;
    margin:50px 30px 50px 300px;
}
.media-container{
    -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
-moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
}

.content-feedback{
    margin-left:100px;
}
.buttonEdit{
    width: 300px;
    height: 45px;
    margin-bottom: 15px;
    border: 1px solid #28a745;
    background: #ffffff;
    color: #28a745;
    font-size: 20px;
    font-weight: 500;
    border-radius: 5px;
}
.buttonEdit:hover{
    color:#ffffff;
    background-color:#28a745;
}
.feedback-form{
    margin-left:500px;
    margin-bottom: 100px;
}
</style>