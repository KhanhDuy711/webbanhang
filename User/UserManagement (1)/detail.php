<?php
session_start();

if (!isset($_SESSION["detail_error"])) {
    $_SESSION["detail_error"] = "";
}
require_once("connect.php");
$pid = $_REQUEST["pid"];
$sql = "select p.*,s.* from product p
        left join sale s on s.saleid = p.saleid
        where pid = '" . $pid . "'";
$result = $conn->query($sql) or die($conn->error);
$row = $result->fetch_assoc();



$sql = "select * from product_size a, size b where a.sizeid=b.sizeid and pid = '" . $pid . "'";
$result2 = $conn->query($sql) or die($conn->error);
// Fetch feedback for the product
$sqlFeedback = "SELECT f.*, c.cname
                FROM feedback f
                JOIN product_receipt pr ON f.id = pr.id
                JOIN receipt r ON r.rid = pr.rid
                JOIN customer c ON c.cid = r.cid
                JOIN product p ON p.pid = pr.pid
                WHERE pr.pid = '" . $pid . "'";
$resultFeedback = $conn->query($sqlFeedback) or die($conn->error);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chi tiết sản phẩm</title>


</head>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
  $("#search-box").keyup(function() {
    $.ajax({
      type: "GET",
      url: "UserManagement (1)/readProduct.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function() {
        $("#search-box").css("background", "#FF0000 url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#search-box").show();
        $("#search-box").html(data);
        $("#search-box").css("background", "#FFF");
      }
    });
  });

  $("#search-button").click(function() {
    var searchKeyword = $("#search-box").val();

    // Save the search keyword in the session using AJAX
    $.ajax({
      type: "POST",
      url: "UserManagement (1)/saveSearchKeyword.php", // Create this file to handle saving the keyword
      data: {
        keyword: searchKeyword
      },
      success: function(response) {
        console.log(response); // You can log the response for debugging
      }
    });

    // Redirect to product_detail_search.php with the search keyword
    window.location.href = 'UserManagement (1)/product_search.php?keyword=' + searchKeyword;
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

<body>
  <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="index.css">
  <header>
    <div class="container-fluid index" id="index">
      <!--HEADER-->
      <?php include_once("header_da_2.php"); ?>
      
    </div>
  </header>
  <?php include("navigation_da_2.php"); ?>

  <div class="product-details">
    <div class="pimage">
      <img style=" width:100%;" src="data:image/jpeg;base64,<?php echo $row['pimage'] ?>">
    </div>
    <div class="detail-content">
      <div class="pname">
        <?php echo $row["pname"] ?>
      </div>
      <div class="psellprice">
        Giá bán:
        <span style="font-size: 40px;
                margin-left: 20px;
                font-weight: 500;
                color: red;">
          <?php
        if ($row["saleid"] != null) {
            // If there is a sale, calculate the discounted price
            $discountedPrice = $row["poriginalprice"] - ($row["poriginalprice"] * $row["salepercent"] / 100);
            echo number_format($discountedPrice) . " VNĐ (";
            echo (date("d/m/Y", strtotime($row["salebegin"])) . " - " . date("d/m/Y", strtotime($row["saleend"])). ")");
        } else {
            // If there is no sale, display the regular selling price
            echo number_format($row["psellprice"]) . " VNĐ";
        }
        ?>
        </span>
      </div>
      <div class="poriginalprice">
        <?php if ($row["saleid"] != null) : ?>
        Giá gốc:
        <span style="font-size: 25px;
                margin-left: 30px;
                font-weight: 400;
                text-decoration: line-through;">
          <?php echo number_format($row["poriginalprice"]) ?> VNĐ
        </span>
        <?php endif; ?>
      </div>

      <div class="pquantity" style="color: #5F4C0B;font-weight: bold;margin-bottom:10px">
        <?php if ($row["pquantity"] <= 0): ?>
            Số lượng trong kho:
            <span style="color: red; font-size: 18px;">Tạm hết hàng</span>
        <?php else: ?>
            Số lượng trong kho:
            <span style="color: #1f1cfd; font-size: 18px;">
                <?php echo $row["pquantity"]; ?>
            </span>
        <?php endif; ?>
    </div>

      <hr>

      <form action="AddToCart.php?pid=<?php echo $pid ?>" method="post">
        <div class="quantity" style="margin-top: 10px;
                font-size: 20px;
                font-weight: 500;">
          Số lượng mua:
          <input type="number" name="quantity" value="" required>
        </div>
        <div class="product-color" style="margin-top: 10px;
                font-size: 20px;
                font-weight: 500;">
          Color:
          <div>
            <?php
                        $sql = "select * from product_color a, color b where a.colorid=b.colorid and pid = '" . $pid . "'";
                        $result1 = $conn->query($sql) or die($conn->error);
                        while ($row1 = $result1->fetch_assoc()) {
                        ?>
            <label class="container">
              <input type="radio" name="color" value="<?php echo $row1["colorname"] ?>" required>
              <span class="checkmark" style="font-size: 18px;font-weight: nomal;">
                <?php echo $row1["colorname"] ?>
              </span>
            </label>
            <?php
                        }
                        $result1->close();
                        ?>
          </div>
        </div>
        <div class="product-size" style="margin-top: 10px;
                font-size: 20px;
                font-weight: 500;">
          Size:
          <div>
            <?php
                        while ($row2 = $result2->fetch_assoc()) {
                        ?>
            <label class="container">
              <input type="radio" name="size" value="<?php echo $row2["sizename"] ?>" required>
              <span class="checkmark" style="font-size: 18px;font-weight: nomal;">
                <?php echo $row2["sizename"] ?>
              </span>
            </label>
            <?php
                        }
                        $result2->close();
                        ?>
          </div>
        </div>
        <font color=red><?php echo $_SESSION["detail_error"]; ?></font>
        <div class="btn-submit">
          <input type="submit" value="Thêm vào giỏ hàng" class="buttonEdit">
        </div>


      </form>

      <div class="pdesc" style="margin-top: 20px;
                font-size: 20px;
                font-weight: 500;">
        Mô tả chi tiết:
        <span style="font-size: 18px;font-weight: 300;">
          <?php echo $row["pdesc"] ?>
        </span>

      </div>
    </div>
  </div>

  <?php if (!empty($row["salebegin"]) && !empty($row["saleend"])): ?>
    <div class="product-feedback" style="margin-left:150px; margin-bottom:100px">
        <h3>Ghi chú: Ngoài thời gian sale 
            <span class="sale-time">
                <?php echo '"' . date("d/m/Y", strtotime($row["salebegin"])) . " - " . date("d/m/Y", strtotime($row["saleend"])) . '"'; ?>
            </span>
            thì các sản phẩm vẫn tính giá gốc
        </h3>
    </div>
<?php endif; ?>


  <div class="container-fluid index" id="index">
    <footer>
      <?php include_once("footer.php"); ?>
    </footer>
  </div>
</body>

</html>
<?php
unset($_SESSION["detail_error"]);
?>
<style>
.sale-time {
  color: red;
  font-weight: bold;
}

.product-details {
  display: flex;
  margin: 100px 150px 100px;
}

.pimage {
  width: 540px;
  height: 540px;
  -webkit-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.47);
  -moz-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.47);
  box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.47);
}

.detail-content {
  margin-left: 100px;
}

.detail-content .pname {
  font-size: 45px;
  font-weight: 600;
  font-family: fangsong;
  text-align: center;
  margin-bottom: 10px;
}

.buttonEdit {
  width: 300px;
  height: 45px;
  margin-top: 15px;
  margin-bottom: 15px;
  border: 1px solid #28a745;
  background: #ffffff;
  color: #28a745;
  font-size: 20px;
  font-weight: 500;
  border-radius: 5px;
}

.buttonEdit:hover {
  color: #ffffff;
  background-color: #28a745;
}
</style>