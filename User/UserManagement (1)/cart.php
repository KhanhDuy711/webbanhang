<?php
session_start();
// unset($_SESSION["cid"]);
// unset($_SESSION["cname"]);
require_once("connect.php");
//xử lý đối với giỏ hàng
$check = false;

if (!isset($_SESSION["error"])) {
	$_SESSION["error"] = "";
}
if (isset($_SESSION["cid"])) {
	// The customer is logged in
	$customer_id = $_SESSION["cid"];
	$customer_name = $_SESSION["cname"];
	// You can use $customer_id and $customer_name as needed
} else {
	// The customer is not logged in
}
if (!empty($_GET["action"])) {
	switch ($_GET["action"]) {


		case "remove":
			if (!empty($_SESSION["cart_item"])) {
				foreach ($_SESSION["cart_item"] as $k => $v) {
					if ($_GET["code"] == $k) {
						unset($_SESSION["cart_item"][$k]);
					}
					if (empty($_SESSION["cart_item"])) {
						unset($_SESSION["cart_item"]);
					}
				}
			}
			break;
		case "empty":
			unset($_SESSION["cart_item"]);
			break;
	}
}
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
<html>

<head>
  <meta charset="utf-8">
</head>

<body>
  <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="../index.css">
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
  }

  header,
  footer {
    background-color: #333;
    color: #fff;
    padding: 10px;
  }

  #shopping-cart {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
  }

  .tbl-cart {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  .tbl-cart th,
  .tbl-cart td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
  }

  .tbl-cart th {
    background-color: #333;
    color: #fff;
  }

  .cart_item-image {
    max-width: 100%;
    height: auto;
  }

  #btnPayment,
  #btnEmpty {
    display: inline-block;
    margin-top: 10px;
    padding: 10px;
    background-color: #12bd1b;
    color: #fff;
    text-decoration: none;
    border-radius: 3px;
    margin-right: 10px;
  }

  #btnEmpty {
    background-color: #d9534f;
  }

  #btnPayment:hover,
  #btnEmpty:hover {
    background-color: #1e7e34;
  }

  font[color="red"] {
    color: red;
  }
  </style>
  <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="../index.css">
  <header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("header_da_2.php"); ?>
    </div>
  </header>

  <!-- NAVIGATION -->
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

  <div id="shopping-cart">
    <div class="txt-heading">Shopping Cart

    </div>
    <a id="btnPayment" href="PaymentPage.php">Payment</a>
    <a id="btnEmpty" href="?action=empty">Empty Cart</a>
    <font color=red><?php echo $_SESSION["error"]; ?></font>
    <br>

    <?php
		$total_quantity = 0;
		$total_price = 0;
		?>
    <table class="tbl-cart" border=1>
      <tr>
        <th>Sản phẩm</th>
        <th>Size</th>
        <th>Màu sắc</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Thành tiền</th>
        <th>Remove</th>
      </tr>
      <?php
			if (!empty($_SESSION["cart_item"])) {
				foreach ($_SESSION["cart_item"] as $item) {
					$item_price = $item["pquantity"] * $item["pprice"];
			?>
      <tr valign=middle>
        <td><img width=50px src="data:image/jpeg;base64,<?php echo $item['pimage'] ?>"
            class=" cart_item-image"><?php echo $item["pname"]; ?></td>
        <td><?php echo $item["size"]; ?></td>
        <td><?php echo $item["color"]; ?></td>
        <td align=right><?php echo $item["pquantity"]; ?></td>
        <td align=right><?php echo $item["pprice"]; ?></td>
        <td align=right><?php echo number_format($item_price, 0) . "VNĐ"; ?></td>
        <td><a href="?action=remove&code=<?php echo $item["code"]; ?>">Remove</a></td>
      </tr>
      <?php
					$total_quantity += $item["pquantity"];
					$total_price += $item_price;
				}
			}
			unset($_SESSION["error"]);
			?>

      <tr>
        <td colspan=2>Total:</td>
        <td align=right><?php echo $total_quantity; ?></td>
        <td></td>
        <td></td>
        <td align=right><strong><?php echo number_format($total_price, 0) . "VNĐ"; ?></strong></td>
      </tr>
    </table>
  </div>
  <div class="container-fluid index" id="index">
    <footer>
      <?php include_once("footer.php");  ?>
    </footer>
  </div>
</body>

</html>




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
    window.location.href = './product_search.php?keyword=' + encodedKeyword;
  }

  // Update the input event to trigger only if there's manual input
  $("#search-input").on('input', function() {
    keyword = $(this).val();

    $.ajax({
      type: "POST",
      url: "UserManagement (1)/saveSearchKeyword.php",
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
        url: "UserManagement (1)/readProduct.php",
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