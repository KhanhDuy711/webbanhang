<?php
session_start();
require_once("Connect.php");


// Check if the search keyword is set
if (isset($_REQUEST['keyword'])) {
    $searchKeyword = $_REQUEST['keyword'];

    // Fetch products based on the provided search query
    $stmt = $conn->prepare("SELECT p.*, s.*, c.catename
                           FROM product p
                           LEFT JOIN sale s ON s.saleid = p.saleid
                           LEFT JOIN product_categories pc ON p.pid = pc.pid
                           LEFT JOIN categories c ON pc.cateid = c.cateid
                           WHERE p.pname LIKE ? and p.pstatus=1");

    // Add "%" to search for products with names containing the search query
    $search_param = "%" . $searchKeyword . "%"; // Change $search_query to $searchKeyword
    $stmt->bind_param("s", $search_param);

    if (!$stmt->execute()) {
        echo "Execution failed: " . $stmt->error;
        exit();
    }

    $result = $stmt->get_result();
}




?>
<?php
// Assuming you have a database connection, replace "your_db_connection" with your actual connection variable.
$conn1 = mysqli_connect("localhost:3306", "root", "", "do_an_web");

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result1 = mysqli_query($conn1, $query);

// Check if there are categories
if ($result1 && mysqli_num_rows($result1) > 0) {
    $categories = mysqli_fetch_all($result1, MYSQLI_ASSOC);
} else {
    $categories = array(); // Default to an empty array if no categories found
}
?>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="../index.css">
  <link rel="stylesheet" type="text/css" href="../css/index_product.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>

<body>

  <header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("header_da_2.php"); ?>
    </div>
  </header>

  <!-- Thanh navigation -->
  <div id="navigation_bar">
    <ul id="nav">
      <li><a href="http://localhost/webbanhang/webbanhang/User/">Trang chủ</a></li>
      <li id="more-btn">
        <!-- Display categories dynamically -->
        <ul>
          <?php foreach ($categories as $category) : ?>
          <a class="categories-list uppercase-title"
            href="AllProduct.php?cateid=<?php echo $category['cateid']; ?>&catename=<?php echo $category['catename']; ?>">
            <?php echo $category['catename']; ?>
          </a>


          <?php endforeach; ?>
        </ul>
      </li>
      <li><a href="SaleProduct.php">Khuyến mại</a></li>
    </ul>
    <a href="./cart.php" class="btn-cart-stop">
      <i class="fas fa-shopping-cart" style="width:50px;"></i>
    </a>
    <div class="search-container" style="margin-right:10px;">
      <button class="fas fa-search" id="search-icon"></button>
      <input type="text" id="search-input" placeholder="Search...">
      <div id="search-suggestion"></div>
      <input type="submit" value="Search" id="search-button">
    </div>


  </div>




  <div id="content">
    <div class="container-fluid content-product">
      <br>
      <!--- Sản Phẩm Bán Chạy-->
      <div class="container-fluid  top-sold row">
        <p class="text-center title ">KẾT QUẢ TÌM KIẾM SẢN PHẨM <span
            class="sale-time uppercase-title"><?php echo $searchKeyword; ?></span></p>
        <hr class="hr-arrival">
        <br>
        <div class="col-md-1 col-sm-1"></div>
        <div class="col-md-10 col-sm-10 row top-sold-content " id="bestseller">
          <?php
                        while ($row_best_seller = $result->fetch_assoc()) {
                            $name_product_best_sell = $row_best_seller['pname'];
                            $price_original = $row_best_seller['poriginalprice'];
                            $price_product_best_sell = $row_best_seller['psellprice'];
                            $url_product_best_sell = $row_best_seller['pimage'];
                            $id_product_best_sell = $row_best_seller['pid'];
                            //Lay thông tin cac san pham giam gia neu co
                            $getSale = $row_best_seller['saleid'];
                            $idsale = "";
                            $notificationfoot = "";
                            $notificationhead = "";
                            $notificationpercent = "";
                            if ($getSale != null) {
                                $getpercentSale = $row_best_seller['salepercent'];
                                $idsale = $getSale;
                                //Hien BadGe thong bao % giam gia
                                $notificationhead = '  <div class="percent-sale">-';
                                $notificationfoot = '%</div> ';
                                $notificationpercent = $getpercentSale;
                            }

                        ?>
          <div class="col-md-4 col-sm-12 text-center top-sold-product  css-rieng">
            <div class="  top-sold-items">
              <?php echo $notificationhead;
                                    echo $notificationpercent;
                                    echo $notificationfoot; ?>
              <img src="data:image/jpeg;base64,<?php echo $url_product_best_sell; ?>" class="img-fluid img-top-sold">
              <div class="overlay">
                <a class="info" href="./detail.php?pid=<?php echo $id_product_best_sell ?>">Chi Tiết</a>
              </div>
            </div>
            <div class="top-sold-infor">
              <?php echo "<h2 style='font-weight:bold;'>" . $name_product_best_sell . "</h2>" ?>
              <p style="margin-bottom: 1ex;">
                <b class="price "
                  style="font-size:15px; text-decoration:line-through; font-weight:600;"><?php echo number_format($price_original) ?>
                  VNĐ </b>
                <br>
                <b class="price "
                  style="color: red; font-size:22px;"><?php echo number_format($price_product_best_sell) ?> VNĐ </b>
              </p>
            </div>
          </div>
          <?php
                        }
                        ?>
        </div>






      </div>


    </div>


    <br><br>

  </div>

  <!-- Footer -->
  <div class="container-fluid index" id="index">
    <footer>
      <?php include_once("./footer.php");  ?>
    </footer>
  </div>


</body>

</html>
<style>
.uppercase-title {
  text-transform: uppercase;
}

.sale-time {
  color: red;
  font-weight: bold;
}

.uppercase-title {
  text-transform: uppercase;
}



.categories-list:hover {
  background-color: darkgray;
}

.categories-list {
  color: #f0f0f0;
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

a.btn-cart-stop {
  margin-left: auto;
  color: #ffffff;
  margin-top: 10px;
  margin-right: 10px;

  /* Push the cart to the right */
}

.search-container {
  display: flex;
  align-items: center;
}

.search-container i {
  margin-left: 10px;
  /* Adjust the margin to position the search icon */
  cursor: pointer;
}

#search-box {
  display: none;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

#search-button {
  display: none;
}

#search-input {
  position: relative;
  display: none;
}

#search-suggestion {
  position: absolute;
  left: 1623px;
  top: calc(100% - 18px);
  width: 202px;
  background-color: white;
  border-radius: 2px;
  box-shadow: 0 1px 5px #ccc;
  display: none;
  z-index: 2;
}

#search-suggestion ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

#search-suggestion li {
  padding: 10px;
  cursor: pointer;
  border-top: 1px solid #ccc;
}

#search-suggestion li:hover {
  background-color: red;
}
</style>