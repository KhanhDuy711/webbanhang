<?php
session_start();
include "connect.php";

if (isset($_SESSION["cid"])) {
    // The customer is logged in
    $customer_id = $_SESSION["cid"];
    $customer_name = $_SESSION["cname"];
    // You can use $customer_id and $customer_name as needed
} else {
    header("location:Login.php");
}
// ... (Những phần mã nguồn hiện có)
$rsAddresses = $conn->query("SELECT caddress FROM customer WHERE cid = $customer_id");
// Lấy danh sách voucher
$rsVouchers = $conn->query("SELECT * FROM voucher");
?>

<html>

<head>
    <meta charset="utf-8">
    <style style="text/css">
    </style>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../index.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!-- Modal -->
<div class="modal fade" id="orderConfirmationModal" tabindex="-1" aria-labelledby="orderConfirmationLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderConfirmationLabel">Xác nhận đặt hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn đặt hàng không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirmOrderButton">Đặt Hàng</button>
      </div>
    </div>
  </div>
</div>

    <header>
        <div class="container-fluid index" id="index">
        <!--HEADER-->
        <?php include_once("header_da_2.php"); ?>
        </div>
  </header>
  <?php include("navigation_da_2.php"); ?>
    <!-- (Những phần mã nguồn hiện có) -->

    <!-- Mã HTML -->
    <form method="post" action="process_order.php">

        <!-- Hiển thị địa chỉ hiện tại -->
        <label for="address">Địa Chỉ Hiện Tại:</label>
        <?php
        // Lấy địa chỉ từ kết quả truy vấn
        $row = $rsAddresses->fetch_assoc();
        $currentAddress = $row['caddress'];
        echo '<input type="text" name="address" value="' . $currentAddress . '" required>';
        ?>
        <br>
        <!-- Chọn phương thức thanh toán -->
        <label for="payment_method">Chọn Phương Thức Thanh Toán:</label>
        <select name="payment_method" id="payment_method">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="cash_on_delivery">Cash on Delivery</option>
        </select>
        <br>

        <!-- Chọn voucher -->
        <label for="voucher">Chọn Voucher:</label>
        <select name=voucher id="voucherSelect" >
            <option value="0">--Áp voucher--</option>
            <?php
            while ($rowVoucher = $rsVouchers->fetch_assoc()) {
                if ($rowVoucher["vremaining"] == 0 || $rowVoucher["vstatus"]!="Active" || $rowVoucher["vend"]<date("Y-m-d")){
                    continue;
                }
                echo "<option value=" . $rowVoucher["vid"];
                echo ">" . $rowVoucher["vname"] . "</option>";
            }

            ?>
        </select>
        <br>

        <!-- Các phần khác của form -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#orderConfirmationModal">
        Đặt Hàng
        </button>


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
                        <td><img width=50px src="data:image/jpeg;base64,<?php echo $item["pimage"]; ?>" class="cart_item-image">
                            <?php echo $item["pname"]; ?>
                        </td>
                        <td>
                            <?php echo $item["size"]; ?>
                        </td>
                        <td>
                            <?php echo $item["color"]; ?>
                        </td>
                        <td align=right><?php echo $item["pquantity"]; ?></td>
                        <td align=right><?php echo $item["pprice"]; ?></td>
                        <td align=right><?php echo "$" . number_format($item_price, 0); ?></td>
                        <td><a href="?action=remove&code=<?php echo $item["code"]; ?>">Remove</a></td>
                    </tr>
                    <?php
                    $total_quantity += $item["pquantity"];
                    $total_price += $item_price;
                }
            }
            ?>
            <tr>
                <td colspan=2>Tổng tiền:</td>
                <td align=right><?php echo $total_quantity; ?></td>
                <td></td>
                <td colspan="3" align=right><strong><?php echo $total_price . "VND" ?></strong></td>

            </tr>
            <tr>
                <td colspan=2>Tiền giảm giá:</td>
                <td align=right></td>
                <td></td>
                <td colspan="3" align=right><strong><span id="discount_price"></span></strong></td>

            </tr>
            <tr>
                <td colspan=2>Thành tiền:</td>
                <td align=right></td>
                <td></td>
                <td colspan="3" align=right><strong><span id="total_price"></span></strong></td>

            </tr>
        </table>
        <!-- gửi đi giá trị total_price -->
        <input type="hidden" name="total_price" id="hidden_total_price" value="<?php echo $total_price; ?>">
    </form>
    <script>
        // Bắt sự kiện khi người dùng thay đổi giá trị của voucher
        document.getElementById('voucherSelect').addEventListener('change', function () {
            // Lấy giá trị của voucher đã chọn
            var selectedVoucher = this.value;

            // Gửi yêu cầu Ajax để lấy vpercent từ database theo vid đã chọn

            if (selectedVoucher !== "") {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_voucher_percent.php?vid=' + selectedVoucher, true);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Nhận phần trăm từ response
                        var voucherPercent = parseFloat(xhr.responseText);

                        // Cập nhật tổng giá trị (total_price) theo phần trăm voucher
                        var total_price_element = document.getElementById('total_price');
                        var total_price = parseFloat("<?php echo $total_price; ?>");
                        var price = parseFloat("<?php echo $total_price; ?>");
                        var discount_price = price * (voucherPercent / 100);
                        // Hiển thị tiền giảm giá 
                        var discount_price_element = document.getElementById('discount_price');
                        discount_price_element.innerHTML = "-" + discount_price.toFixed(0) + "VND";

                        total_price *= (1 - voucherPercent / 100); // Áp dụng giảm giá từ voucher
                        // Hiển thị tổng giá trị mới lên trang
                        total_price_element.innerHTML = total_price.toFixed(0) + "VND";

                        // Cập nhật giá trị trường ẩn
                        var hidden_total_price = document.getElementById('hidden_total_price');
                        hidden_total_price.value = total_price.toFixed(0);
                    }
                };

                xhr.send();
            } else {
                // Nếu không có voucher được chọn, hiển thị tổng giá trị ban đầu
                var total_price_element = document.getElementById('total_price');
                total_price_element.innerHTML = parseFloat("<?php echo $total_price; ?>").toFixed(0) + "VND";

                // Cập nhật giá trị trường ẩn
                var hidden_total_price = document.getElementById('hidden_total_price');
                hidden_total_price.value = parseFloat("<?php echo $total_price; ?>").toFixed(0);
            }
        });
    </script>
    <script>
        document.getElementById('confirmOrderButton').addEventListener('click', function () {
            // Gửi form khi người dùng xác nhận
            document.querySelector('form').submit();
        });
        </script>

    <div class="container-fluid index" id="index">
        <footer>
            <?php include_once("footer.php");  ?>
        </footer>
    </div>
</body>

</html>
<style>
       

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .cart_item-image {
            max-width: 50px;
            max-height: 50px;
        }

        #discount_price, #total_price {
            font-size: 18px;
            color: #4CAF50;
        }
</style>
<?php
?>