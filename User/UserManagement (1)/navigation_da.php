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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/css/all.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
      window.location.href = 'UserManagement (1)/product_search.php?keyword=' + encodedKeyword;
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
</head>

<body>
  <!-- Thanh navigation -->
  <div id="navigation_bar">
    <ul id="nav">
      <li><a href="http://localhost/webbanhang/webbanhang/User/">Trang chủ</a></li>
      <li id="more-btn">
        <!-- Display categories dynamically -->
        <ul>
          <?php foreach ($categories as $category) : ?>
          <a class="categories-list uppercase-title"
            href="UserManagement (1)/AllProduct.php?cateid=<?php echo $category['cateid']; ?>&catename=<?php echo $category['catename']; ?>">
            <?php echo $category['catename']; ?>
          </a>


          <?php endforeach; ?>
        </ul>
      </li>
      <li><a href="UserManagement (1)/SaleProduct.php">Khuyến mại</a></li>
    </ul>
    <a href="UserManagement (1)/cart.php" class="btn-cart-stop">
      <i class="fas fa-shopping-cart" style="width:50px;"></i>
    </a>
    <div class="search-container" style="margin-right:10px;">
      <button class="fas fa-search" id="search-icon"></button>
      <input type="text" id="search-input" placeholder="Search...">
      <div id="search-suggestion"></div>
      <input type="submit" value="Search" id="search-button">
    </div>
  </div>
</body>


</html>

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