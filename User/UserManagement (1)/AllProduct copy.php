<?php

include "Connect.php";

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

// Fetch all products if no category is selected or fetch products based on the selected category with pagination
if (empty($selectedCategoryId)) {
    $rs1 = $conn->query("SELECT p.*, s.*
                        FROM product p
                        LEFT JOIN sale s ON s.saleid = p.saleid
                        LIMIT $offset, $productsPerPage");
} else {
    $stmt = $conn->prepare("SELECT a.*, b.catename, s.*
                           FROM product a
                           LEFT JOIN sale s ON s.saleid = a.saleid
                           JOIN product_categories c ON a.pid = c.pid
                           JOIN categories b ON c.cateid = b.cateid
                           WHERE b.cateid = ?
                           LIMIT $offset, $productsPerPage");
    $stmt->bind_param("s", $selectedCategoryId);
    $stmt->execute();
    $rs1 = $stmt->get_result();
    $stmt->close();
}

$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc()['total'];

// Calculate the total number of pages
$totalPages = ceil($totalProducts / $productsPerPage);
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

 <!-- Header -->
 <header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("../UserManagement (1)/header.php"); ?>
    </div>
  </header>

  <!-- Thanh navigation -->

  
  <div id="navigation_bar">
            <ul id="nav">
                <li><a href="../index.php">Trang chủ</a></li>
                <li  id="more-btn">
                    <a href="#">
                        Sản phẩm
                        <i class="nav-arrow-down  ti-angle-down"></i>
                    </a>
                    
                </li>
                <li><a href="#">Khuyến mại</a></li>
               
                
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
  
    
        <div class="clear"></div>
        <?php
            if ($rs1->num_rows == 0) {
                echo "<br>Không có sản phẩm theo nhóm. Vui lòng chọn nhóm khác!";
            } else {
        ?>
        <ul class="ul product-list  apple-product  product-list-full" id="product-list-home1144" style="min-height:200px;display: flex;flex-wrap: wrap;">
            <?php
                while ($row1 = $rs1->fetch_assoc()) {
            ?>
            <li onclick="" class="p-item-group">
            <div class="p-container">
                <a href="detail.php?pid=<?php echo $row1["pid"]; ?>" class="p-img">
                    <?php
                     $imagePath = 'uploads/' . $row1["pimage"];
                     echo '<img src="' . $imagePath . '" alt="Product image" width="200px">';
                    ?>
                </a>
                <a href="detail.php?pid=<?php echo $row1["pid"]; ?>" class="p-name">
                    <?php echo $row1["pname"]; ?>
                </a>
                <br>
                <span class="p-oldprice2"></span>
               <!-- Inside the while loop where you display each product -->
                <?php
                if (!empty($row1["salepercent"])) {
                    $saleHasExpired = !($row1["salebegin"] <= date('Y-m-d H:i:s') && $row1["saleend"] >= date('Y-m-d H:i:s'));

                    if (!$saleHasExpired) {
                        // Display discounted price with a strikethrough for the original price
                        echo '<span class="p-price2"><del>Giá gốc: ' . number_format($row1["poriginalprice"]) . '₫</del></span>';
                        echo '<span class="p-price2"><i>Giá sau sale:</i>' . number_format($row1["psellprice"]) . '₫</span>';
                    } else {
                        // If the sale has expired, only display the original price
                        echo '<span class="p-price2"><i>Giá bán:</i>' . number_format($row1["poriginalprice"]) . '₫</span>';
                    }
                } else {
                    // If there is no sale, display the original price as the selling price
                    echo '<span class="p-price2"><i>Giá bán:</i>' . number_format($row1["poriginalprice"]) . '₫</span>';
                }
                ?>

                            <span class="p-bottom">
                    <span style="color: #12bd1b;">
                        <?php
                            echo ($row1["pquantity"] > 0) ? "✔ Có hàng" : "Hết hàng";
                        ?>
                    </span>
                    </span>
                </div>
            </li>
            <?php
                }
                $rs1->close();
            }
            $conn->close();
        ?>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="?cateid=' . $selectedCategoryId . '&page=' . $i . '">' . $i . '</a>';
        }
        ?>
    </div>
    
<!-- Footer -->
<div class="container-fluid index" id="index">
    <footer>
        <?php include_once("../UserManagement (1)/footer.php");  ?>
    </footer>
  </div>

</body>

</html>

<?php
?>

<script>
$(document).ready(function () {
    var keyword = '';
    var showSuggestions = false; // Flag to track whether to show suggestions

    // Khi click vào icon search
    $('#search-icon').click(function () {
        $('#search-input').toggle();
        $('#search-button').toggle();
        showSuggestions = !showSuggestions; // Toggle the flag

        // Check if the keyword is not empty before making the AJAX request
        if (showSuggestions && keyword && keyword.trim() !== '') {
            // Simulate search suggestions
            $.ajax({
                type: "GET",
                url: "./readProduct.php",
                data: { keyword: keyword },
                success: function (data) {
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
    $("#search-button").click(function () {
        performSearch();
    });

    // Optionally, you can also trigger the search when pressing Enter in the search input
    $("#search-input").keypress(function (e) {
        if (e.which === 13) {
            performSearch();
        }
    });

    function performSearch() {
        keyword = $("#search-input").val();
        var encodedKeyword = encodeURIComponent(keyword);
        window.location.href = './product_search.php?keyword=' + encodedKeyword;
    }

    // Update the input event to trigger only if there's manual input
    $("#search-input").on('input', function () {
        keyword = $(this).val();

        $.ajax({
            type: "POST",
            url: "UserManagement (1)/saveSearchKeyword.php",
            data: { keyword: keyword },
            success: function (response) {
                console.log(response);
            }
        });

        // Check if the keyword is not empty before making the AJAX request
        if (showSuggestions && keyword && keyword.trim() !== '') {
            // Simulate search suggestions
            $.ajax({
                type: "GET",
                url: "UserManagement (1)/readProduct.php",
                data: { keyword: keyword },
                success: function (data) {
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
    $(document).on('click', '#search-suggestion li', function () {
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
  color:#ffffff;
  margin-top:10px;
  margin-right:10px;
 
  /* Push the cart to the right */
}

.search-container {
  display: flex;
  align-items: center;
}

.search-container i {
  margin-left: 10px; /* Adjust the margin to position the search icon */
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