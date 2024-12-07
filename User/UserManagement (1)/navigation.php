<?php
include "Connect.php";
?>
<nav>
    <a href="../index.php">Trang chủ</a>
    <div class="dropdown">
    <button class="dropbtn" onclick="toggleDropdown()">Sản Phẩm</button>
    <div class="dropdown-content" id="dropdownContent">
        <?php
        $sql = "SELECT * FROM categories";
        $result1 = $conn->query($sql);
        while ($row1=$result1->fetch_assoc()) {
            echo '<a href="?cateid=' . $row1["cateid"] . '">' . $row1["catename"] . '</a>';
        }
        ?>
    </div>
    <?php
    ?>
</div>
    <a href="sale.php">Sale</a>
    <a href="hot.php">Hot</a>
    
    <a href="cart.php" class="btn-cart-stop">
        <i class="fas fa-shopping-cart"></i>
    </a>
    <div class="search-container">
        <button class="fas fa-search" id="search-icon"></button>
        <input type="text" placeholder="Tìm kiếm..." id="search-box">
        <input type="submit" value="Search" id="search-button">
    </div>
</nav>

  <style>
    nav {
    background-color: #333;
    color: white;
    padding: 10px;
    position: sticky;  
    top: 0;
    z-index: 8;
    display: flex;
    justify-content: space-between; /* Align items to the start and end of the nav bar */
    align-items: center;
    }

    nav a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    }

    nav a:hover {
    text-decoration: underline;
    }

    nav a.btn-cart-stop {
    margin-left: auto; /* Push the cart to the right */
    }

    nav .search-container {
    display: flex;
    align-items: center;
    }

    nav .search-container i {
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
    /* Add this to your existing styles */
.dropdown {
    display: inline-block;
}

.dropbtn {
    background-color: #333;
    color: white;
    padding: 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}

  </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
  $(document).ready(function(){
$("#search-box").keyup(function(){
  $.ajax({
      type: "GET",
      url: "readProduct.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function(){
          $("#search-box").css("background","#FF0000 url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data){
          $("#search-box").show();
          $("#search-box").html(data);
          $("#search-box").css("background","#FFF");
      }
  });
});

$("#search-button").click(function () {
  var searchKeyword = $("#search-box").val();
  
  // Save the search keyword in the session using AJAX
  $.ajax({
      type: "POST",
      url: "saveSearchKeyword.php", // Create this file to handle saving the keyword
      data: { keyword: searchKeyword },
      success: function(response){
          console.log(response); // You can log the response for debugging
      }
  });

  // Redirect to product_detail_search.php with the search keyword
  window.location.href = 'product_search.php?keyword=' + searchKeyword;
});
});
function selectCountry(val) {
$("#search-box").val(val);
$("#search-box").hide();
}
  </script>
<script>
$(document).ready(function() {
// Khi click vào icon search
$('#search-icon').click(function() {
$('#search-box').toggle();
$('#search-button').toggle(); // Toggle the visibility of the search box
});
});
</script>
