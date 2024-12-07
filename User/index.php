<?php
session_start();
    if(!isset($_SESSION["register_error"])) {
        $_SESSION["register_error"] = "";
    }
include "connect.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="index.css">
  <link rel="stylesheet" type="text/css" href="css/index_product.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>

<body>
  <!-- Header -->
  <header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("UserManagement (1)/header.php"); ?>
    </div>
  </header>

  <!-- Thanh navigation -->


  <?php include_once("UserManagement (1)/navigation_da.php"); ?>

  <center>
    <font color=red><?php echo $_SESSION["register_error"]; ?></font><br>
  </center>



  <!-- Phần chính của trang -->
  <div id="content">
    <?php require("UserManagement (1)/HomePage.php"); ?>
  </div>

  <!-- Footer -->
  <div class="container-fluid index" id="index">
    <footer>
      <?php include_once("UserManagement (1)/footer.php");  ?>
    </footer>
  </div>


</body>
<?php unset($_SESSION["register_error"]); ?>

</html>