<?php
session_start();
include("../../db/MySQLConnect.php");
if(isset( $_SESSION['admin_login']) ) $checklogin=$_SESSION['admin_login'];
if( $_SESSION['admin_login']==false  ) header("Location: ../index.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../../images/header-website.png" type="image/png" />

  <title>Quản lý cửa hàng bán quần áo</title>


  <!-- Custom Theme Style -->
  <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php
        /*Navigation*/
        include("navigation.php");

        /*page content */


        if(!isset($_GET['manage'])) {
          require("ProductManagement.php");}

          else if($_GET['manage']=='product'){
              require("ProductManagement.php");
          }
          else if($_GET['manage']=='Product_trashcan'){
            require("Product_trashcan.php");
           }
          else if($_GET['manage']=='categories'){
              require("CategoriesManagement.php");
          }
 
         else if($_GET['manage']=='color' ) {
             require("ColorManagement.php");
         }
         else if($_GET['manage']=='size' ) {
          require("SizeManagement.php");
        }
          else if($_GET['manage']=='customer' ) {
            require("AccountManagement.php");
        } else if($_GET['manage'] == 'voucher'){
          require("manage_vouchers.php");
        } 
        else if($_GET['manage'] == 'Product_add'){
            require("Product_add.php");
          }
        else if($_GET['manage'] == 'Product_edit'){
          require("Product_edit.php");
        }
        else if($_GET['manage'] == 'Categories_add'){
          require("Categories_add.php");
        }
        else if($_GET['manage'] == 'Categories_edit'){
          require("Categories_edit.php");
        }
        else if($_GET['manage'] == 'Color_add'){
          require("Color_add.php");
        }
        else if($_GET['manage'] == 'Color_edit'){
          require("Color_edit.php");
        }
        else if($_GET['manage'] == 'Size_add'){
          require("Size_add.php");
        }
        else if($_GET['manage'] == 'Size_edit'){
          require("Size_edit.php");
        }
        else if($_GET['manage'] == 'Voucher_add'){
          require("add_voucher.php");
        }
        else if($_GET['manage'] == 'update_voucher_status'){
          require("update_voucher_status.php");
        }
        else if($_GET['manage'] == 'order'){
          require("manage_orders.php");
        }
        else if($_GET['manage'] == 'update_order_status'){
          require("update_order_status.php");
        }
        else if($_GET['manage'] == 'sale') {
          require("SaleManagement.php");
        }
        else if($_GET['manage'] == 'Sale_add') {
          require("Sale_add.php");
        }
        else if($_GET['manage'] == 'Sale_edit') {
          require("Sale_edit.php");
        }
    
        else if($_GET['manage'] == 'Categories_trashcan') {
          require("Categories_trashcan.php");
        }
        else if($_GET['manage'] == 'thongke') {
          require("thongke.php");
        }


       
        /* footer content */
       
     ?>
    </div>
  </div>



</body>

</html>