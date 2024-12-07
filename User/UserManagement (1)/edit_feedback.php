<?php
session_start();
include "Connect.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedbackID = $_POST['fid'];
    $productID = $_POST['pid'];
    $receiptID = $_POST['rid'];
    $existingMedia = $_POST['fmedia'];

    // Fetch existing feedback details
    $queryFeedback = "SELECT * FROM feedback WHERE fid = ?";
    $stmtFeedback = $conn->prepare($queryFeedback);

    if (!$stmtFeedback) {
        die('Error preparing statement for feedback details: ' . $conn->error);
    }

    $stmtFeedback->bind_param("i", $feedbackID);

    if (!$stmtFeedback->execute()) {
        die('Error executing statement for feedback details: ' . $stmtFeedback->error);
    }

    $resultFeedback = $stmtFeedback->get_result();

    // Check if feedback exists
    if ($resultFeedback->num_rows > 0) {
        $existingFeedback = $resultFeedback->fetch_assoc();
    } else {
        echo 'Feedback not found.';
        exit();
    }

    // Close the statement
    $stmtFeedback->close();
}

// Rest of your HTML and form code goes here...
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <!-- Add your styles if needed -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/user.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header id="header">
        <div class="row">
            <div class=" col-md-5 col-sm-0">
                <h5 style="font-style: italic;font-weight: bold;color: #fff;">Hotline: 0123456789</h5>
            </div>
            <div class="Logo-Brand col-md-2 col-sm-5" style="width:300px;height:300px">
                <img src="images/logo2.png " style="width:100%">
            </div>
            <div class=" col-md-1 col-sm-0"></div>
            <div class="Account col-md-4 col-sm-5">

                <?php
                if (isset($_SESSION["cid"])) {
                    // The customer is logged in
                    $customer_id = $_SESSION["cid"];
                    $customer_name = $_SESSION["cname"];
                    // You can use $customer_id and $customer_name as needed
                }
                ?>

                <button class="login btn btn-outline-success">
                    <a style="text-decoration: none; color: green; font-weight: bold;" href='<?php
                    if (isset($_SESSION['cid'])) {
                        if ($_SESSION['cid']) {
                            echo "#"; // Link tới trang hiển thị thông tin khách hàng
                        } else {
                            echo "UserManagement (1)/Login.php"; // Link tới trang đăng nhập
                        }
                    } else {
                        echo "UserManagement (1)/Login.php"; // Link tới trang đăng nhập (mặc định)
                    }
                    ?>'>
                        <?php
                        if (isset($_SESSION['cid'])) {
                            if ($_SESSION['cid']) {
                                echo '<i class="fas fa-user-alt"></i> ' . $customer_name; // Hiển thị tên khách hàng khi đã đăng nhập
                            } else {
                                echo "Đăng Nhập"; // Hiển thị khi chưa đăng nhập
                            }
                        } else {
                            echo "Đăng Nhập"; // Hiển thị khi chưa đăng nhập
                        }
                        ?>
                    </a>
                </button>
                <button class="btn btn-outline-danger register"><a class="register"
                        style="text-decoration: none; color: red;font-weight:bold;" href='<?php
                        if (isset($_SESSION['cid'])) {
                            if ($_SESSION['cid'])
                                echo "logout.php";
                            else
                                echo "register.php";
                        } else
                            echo "register.php";
                        ?>'>
                        <?php
                        if (isset($_SESSION['cid'])) {
                            if ($_SESSION['cid'])
                                echo "Đăng Xuất";
                            else
                                echo "Đăng Ký";
                        } else
                            echo "Đăng Ký";

                        ?>
                    </a>
                </button>

            </div>

        </div>


    </header>

    <!-- Thanh navigation -->

    <div id="navigation_bar">
        <ul id="nav">
            <li><a href="#">Trang chủ</a></li>
            <li id="more-btn">
                <a href="#">
                    Sản phẩm
                    <i class="nav-arrow-down  ti-angle-down"></i>
                </a>
               
            </li>
            <li><a href="#tour">Khuyến mại</a></li>


        </ul>
        <div class="icon">
            <i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i>
        </div>
        <div icon="icon">
            <i class="fa-solid fa-cart-plus" style="color: #ffffff;"></i>
        </div>


    </div>


    <form method="post" action="process_edit_feedback.php" enctype="multipart/form-data">
        <input type="hidden" name="fid" value="<?php echo $feedbackID; ?>">
        <input type="hidden" name="pid" value="<?php echo $productID; ?>">
        <input type="hidden" name="rid" value="<?php echo $receiptID; ?>">
        <input type="hidden" name="fmedia" value="<?php echo $existingMedia; ?>">
        <!-- Populate existing feedback content -->
        <label for="fstar">Comment:</label>
        <textarea name="fcontent" rows="4" cols="50" required><?php echo $existingFeedback['fcontent']; ?></textarea>
        <br>
        <!-- Populate existing star rating -->
        <label for="fstar">Rating:</label>
        <input type="number" name="fstar" min="1" max="5" required value="<?php echo $existingFeedback['fstar']; ?>">
        <br>
        <label>Existing Media:</label>
        <?php
        if (!empty($existingMedia)) {
            $mediaPath = $existingMedia;            
            // Determine if it's an image or video
            $fileExtension = pathinfo($mediaPath, PATHINFO_EXTENSION);
            
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Display image
                echo '<img style="width:60%" src="' . $mediaPath . '" alt="Existing Media">';
            } elseif ($fileExtension === 'mp4') {
                // Display video
                echo '<video controls><source src="' . $mediaPath . '" type="video/mp4"></video>';
            } else {
                echo '<p>Unsupported media type.</p>';
            }
        } else {
            echo '<p>No existing media for this feedback.</p>';
        }
        ?>
        <br>
        <label for="fmedia">Edit Image/Video:</label>
        <input type="file" name="fmedia">
        <br>
        <!-- Add other form fields as needed -->
        <button type="submit">Save Changes</button>
    </form>
    <!-- Footer -->
    <div class="container-fluid index" id="index">
        <footer>
            <?php include_once("footer.php"); ?>
        </footer>
    </div>

</body>

</html>

<?php
// Close your database connection
$conn->close();
?>
<style>
    .media-container{
    -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
-moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.47);
}

    .Edit-Feedback{
        display:flex;
        margin: 100px 350px 100px;
    }
    .content-edit{
        margin-left:100px;
    }
    .buttonEdit{
    width: 400px;
    height: 45px;
    margin-top: 30px;
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


    /* Trích từ phần CSS của bạn và chỉnh sửa để cải thiện giao diện */

/* Định dạng cho header */
#header {
    background-color: #333;
    color: white;
    padding: 10px 0;
}

/* Căn giữa logo */
.Logo-Brand {
    text-align: center;
}

/* Style cho navigation bar */
#navigation_bar {
    position: sticky;
    top: 0;
    background: #333;
    z-index: 8;
}

#nav > li {
    position: relative;
    display: inline-block;
    margin-right: 20px;
}

#nav > li > a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
}

#nav > li:hover > a {
    background-color: #ccc;
    color: black;
}

/* Style cho form và button */
form {
    margin: 40px 600px;
}

textarea, input[type="number"], input[type="file"] {
    margin-bottom: 10px;
}

button[type="submit"] {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #218838;
}

/* Các phần còn lại của trang có thể được chỉnh sửa tương tự để cải thiện giao diện */

/* Thêm các biểu tượng, icon */
.icon i {
    font-size: 20px;
    color: white;
    margin-left: 10px;
}

</style>