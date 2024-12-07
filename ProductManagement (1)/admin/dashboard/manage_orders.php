<?php

include "Connect.php";

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý đơn hàng)


// Lấy danh sách đơn hàng từ bảng receipt
$query = "SELECT rid, rdate, rmoney, rstatus, rpaymentmethod, rdestination FROM receipt";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đơn hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/Product-Management.css">

</head>

<body style="margin-left:100px;background-color: #f1efef">
  <h2 style="margin-top:10px">Quản lý đơn hàng</h2>
  <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
    <tr style="text-align: center;">
      <th scope="col">ID Đơn hàng</th>
      <th scope="col">Ngày đặt hàng</th>
      <th scope="col">Tổng tiền</th>
      <th scope="col">Trạng thái</th>
      <th scope="col">Phương thức thanh toán</th>
      <th scope="col">Nơi nhận</th>
      <th scope="col" colspan="2">Thao tác</th>
    </tr>
    <?php
        while ($row = $result->fetch_assoc()) {
          echo '<tr style="text-align: center;">';
            echo '<td><a href="#orderDetailsContainer" class="orderDetailsLink" data-rid="' . $row['rid'] . '">' . $row['rid'] . '-Chi tiết</a></td>';
            echo "<td>{$row['rdate']}</td>";
            echo "<td>{$row['rmoney']}</td>";
            echo "<td>{$row['rstatus']}</td>";
            echo "<td>{$row['rpaymentmethod']}</td>";
            echo "<td>{$row['rdestination']}</td>";
            echo "<td><button type='button' class='btn btn-warning'><a class='button-edit' href='index.php?manage=update_order_status&rid={$row['rid']}'>Cập nhật trạng thái</a></button></td>";
            echo "<td><button type='button' class='btn btn-success'><a class='button-edit' href='inhoadon.php?rid={$row['rid']}'>In hóa đơn</a></button></td>";
            echo "</tr>";
        }
        ?>
  </table>

  <div id="orderDetailsContainer" class="container-fluid table-order " style="width: 90%;margin-left: 75px;"
    align=center>


  </div>
  <br><br><br><br>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
  // Attach a click event to the order details links
  $('.orderDetailsLink').on('click', function(e) {
    e.preventDefault();

    // Get the order ID from the data-rid attribute
    var rid = $(this).data('rid');
    console.log(rid);
    // Make an AJAX request to fetch the order details
    $.ajax({
      type: 'GET',
      url: 'Order_history_detail.php', // Adjust the URL if needed
      data: {
        rid: rid
      },
      success: function(data) {
        // Update the orderDetailsContainer with the fetched data
        $('#orderDetailsContainer').html(data);
      },
      error: function(error) {
        console.log('Error:', error);
        alert('Error loading order details.');
      }
    });
  });
});
</script>

</html>

<?php
// Đóng kết nối
$connect->close();
?>