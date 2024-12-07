<?php

include "../connect.php";

if (isset($_SESSION["cid"])) {
    // The customer is logged in
    $customer_id = $_SESSION["cid"];
    $customer_name = $_SESSION["cname"];
    // You can use $customer_id and $customer_name as needed
} else {
    // The customer is not logged in
}
// Get the selected category ID
$selectedCategoryId = isset($_REQUEST["cateid"]) ? $_REQUEST["cateid"] : "";

// Set the number of products per page
$productsPerPage = 9;

// Get the current page number from the URL
$pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($pageNumber - 1) * $productsPerPage;

// Update sellPrice
$sqlUpdatePrices = "UPDATE product
                    LEFT JOIN sale ON product.saleid = sale.saleid
                    SET product.psellprice = CASE
                        WHEN (sale.saleid IS NOT NULL) AND (sale.salebegin IS NOT NULL AND sale.saleend IS NOT NULL) AND (sale.salebegin <= NOW() AND sale.saleend >= NOW()) THEN
                            product.poriginalprice - (product.poriginalprice * sale.salepercent / 100)
                        ELSE
                            product.poriginalprice
                    END";
$resultUpdatePrices = $conn->query($sqlUpdatePrices);
// Check if category_id is present in the URL
if (isset($_GET['cateid'])) {
    // Get the category_id from the URL
    $cateID = $_GET['cateid'];
    $catename = $_GET['catename'];
    // Use the category_id in your SQL query
    $rs1 = $conn->query("SELECT p.*, s.*
                        FROM product_categories pc
                        LEFT JOIN product p ON pc.pid = p.pid
                        LEFT JOIN categories c ON pc.cateid = c.cateid
                        LEFT JOIN sale s ON s.saleid = p.saleid
                        WHERE p.pstatus=1 AND pc.cateid = $cateID
                        LIMIT $offset, $productsPerPage");
} else {
    // Handle the case when category_id is not present in the URL
    // You may show an error message or redirect to a default category
    $rs1 = $conn->query("SELECT p.*, s.*
    FROM product_categories pc
    LEFT JOIN product p ON pc.pid = p.pid
    LEFT JOIN categories c ON pc.cateid = c.cateid
    LEFT JOIN sale s ON s.saleid = p.saleid
    WHERE p.pstatus=1 
    LIMIT $offset, $productsPerPage");
}



$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc()['total'];

$totalPages = ceil($totalProducts / $productsPerPage);
?>

<?php
// Assuming you have a database connection, replace "your_db_connection" with your actual connection variable.
$conn = mysqli_connect("localhost:3306", "root", "", "do_an_web");

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

// Check if there are categories
if ($result && mysqli_num_rows($result) > 0) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $categories = array(); // Default to an empty array if no categories found
}
?>

<!--SELECT p.MA_PN,ct.MA_SP,sp.TEN_SP,sp.DON_GIA,sp.HINH_ANH_URL  FROM phieunhap as p inner join chitietphieunhap as ct inner join  sanpham as sp on p.MA_PN = ct.MA_PN and ct.MA_SP=sp.MA_SP  
WHERE p.NGAY_NHAP BETWEEN '2020-02-01' AND '2021-05-05' GROUP BY sp.TEN_SP ORDER BY p.NGAY_NHAP DESC 

	//SELECT p.PHAN_TRAM_GIAM_GIA,od.MA_CTGG,od.MA_SP from chuongtrinhgiamgia as p inner join chitietgiamgia as od on p.MA_CTGG = od.MA_CTGG GROUP BY od.MA_SP-->
<style>
.css-rieng {

  border-radius: 10px;


}
</style>


<?php
session_start();
include "connect.php";
?>
<!DOCTYPE html>
<html lang="en">

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
  <!-- Header -->
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







  <!-- Phần chính của trang -->
  <div id="content">
    <div class="container-fluid content-product">


      <br>

      <!--- Sản Phẩm Bán Chạy-->

      <div class="container-fluid  top-sold row">
        <p class="text-center title uppercase-title">Danh mục sản phẩm <span
            class="sale-time"><?php echo $catename?></span></p>
        <hr class="hr-arrival">
        <br>
        <div class="col-md-1 col-sm-1"></div>
        <div class="col-md-10 col-sm-10 row top-sold-content " id="bestseller">
          <?php
                    while ($row_best_seller = $rs1->fetch_assoc()) {
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
              <p class="price-info" style="margin-bottom: 1ex;">
                <?php if ($idsale != ""): ?>
                <b class="original-price"
                  style="font-size:15px; text-decoration:line-through; font-weight:600;"><?php echo number_format($price_original) ?>
                  VNĐ </b>
                <?php endif; ?>
                <br>
                <b class="sale-price"
                  style="color: red; font-size:22px;"><?php echo number_format($price_product_best_sell) ?> VNĐ </b>
              </p>
            </div>
          </div>
          <?php
                    }
                    ?>
        </div>
        <div class="col-md-1 col-sm-1"></div>






      </div>
    </div>


  </div>

  <div class="phantrang">
    <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a class="nutphantrang" href="?cateid=' . $selectedCategoryId . '&page=' . $i . '&catename=' . $catename . '">' . $i . '</a>';
        }
        ?>
  </div>
  <br><br>

  <!-- Footer -->
  <div class="container-fluid index" id="index">
    <footer>
      <?php include_once("footer.php");  ?>
    </footer>
  </div>


</body>

</html>

<style>
/* Định dạng cho các nút phân trang */
.uppercase-title {
  text-transform: uppercase;
}

.sale-time {
  color: red;
  font-weight: bold;
}


.categories-list:hover {
  background-color: darkgray;
}

.categories-list {
  color: #f0f0f0;
}

.phantrang {
  text-align: center;
}

.nutphantrang {
  display: inline-block;
  width: 40px;
  /* Độ rộng của mỗi nút phân trang */
  height: 40px;
  /* Chiều cao của mỗi nút phân trang */
  line-height: 40px;
  /* Đảm bảo văn bản được căn giữa */
  border: 1px solid #ccc;
  /* Đường viền của nút phân trang */
  margin: 0 5px;
  /* Khoảng cách giữa các nút */
  text-decoration: none;
  /* Loại bỏ đường gạch chân (link) */
  color: #333;
  /* Màu văn bản của nút phân trang */
  border-radius: 5px;
  /* Bo tròn góc của nút */
  transition: all 0.3s ease;
  /* Hiệu ứng chuyển đổi */
}

.nutphantrang:hover {
  background-color: #f0f0f0;
  /* Màu nền khi rê chuột qua */
}
</style>



<script>
$(document).ready(function() {
  var keyword = '';
  var showSuggestions = false; // Flag to track whether to show suggestions

  // Khi click vào icon search
  $('#search-icon').click(function() {
    $('#search-input').toggle();
    $('#search-button').toggle();
    showSuggestions = !showSuggestions; // Toggle the flag

    // Check if the keyword is not empty before making the AJAX request
    if (showSuggestions && keyword && keyword.trim() !== '') {
      // Simulate search suggestions
      $.ajax({
        type: "GET",
        url: "./readProduct.php",
        data: {
          keyword: keyword
        },
        success: function(data) {
          $("#search-suggestion").html(data);
          $("#search-suggestion").show();
        }
      });
    } else {
      // If the keyword is empty or suggestions should be hidden, hide the suggestions
      $("#search-suggestion").hide();
    }
  });

  // Function to handle search when the button is clicked
  $("#search-button").click(function() {
    performSearch();
  });

  // Optionally, you can also trigger the search when pressing Enter in the search input
  $("#search-input").keypress(function(e) {
    if (e.which === 13) {
      performSearch();
    }
  });

  function performSearch() {
    keyword = $("#search-input").val();
    var encodedKeyword = encodeURIComponent(keyword);
    window.location.href = 'product_search.php?keyword=' + encodedKeyword;
  }

  // Update the input event to trigger only if there's manual input
  $("#search-input").on('input', function() {
    keyword = $(this).val();

    $.ajax({
      type: "POST",
      url: "saveSearchKeyword.php",
      data: {
        keyword: keyword
      },
      success: function(response) {
        console.log(response);
      }
    });

    // Check if the keyword is not empty before making the AJAX request
    if (showSuggestions && keyword && keyword.trim() !== '') {
      // Simulate search suggestions
      $.ajax({
        type: "GET",
        url: "readProduct.php",
        data: {
          keyword: keyword
        },
        success: function(data) {
          $("#search-suggestion").html(data);
          $("#search-suggestion").show();
        }
      });
    } else {
      // If the keyword is empty or suggestions should be hidden, hide the suggestions
      $("#search-suggestion").hide();
    }
  });

  // Update the selectSuggestion function
  $(document).on('click', '#search-suggestion li', function() {
    var selectedSuggestion = $(this).text();
    selectSuggestion(selectedSuggestion);
  });

  function selectSuggestion(val) {
    $("#search-input").val(val);
    $("#search-suggestion").hide();
  }
});
</script>

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