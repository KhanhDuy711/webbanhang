<?php
require_once("Connect.php");

function getReceiptDetails($receiptId) {
    global $connect; // Đã thay đổi từ $conn thành $connect

    // Prepare the query to get receipt details
    $sql_receipt = "SELECT r.rid, r.rdate, r.rmoney, r.rstatus, r.rpaymentmethod, r.rdestination, 
                           c.cname, c.cphone, c.cemail,
                           v.vname, v.vamount, v.vpercent
                    FROM receipt r
                    LEFT JOIN customer c ON r.cid = c.cid
                    LEFT JOIN voucher v ON r.vid = v.vid
                    WHERE r.rid = ?";
                    
    $stmt_receipt = $connect->prepare($sql_receipt); // Đã thay đổi từ $conn thành $connect
    $stmt_receipt->bind_param("s", $receiptId);
    $stmt_receipt->execute();
    $result_receipt = $stmt_receipt->get_result();
    
    if ($result_receipt->num_rows === 0) {
        return "Receipt not found.";
    }

    $receipt = $result_receipt->fetch_assoc();

    // Prepare the query to get product details within the receipt
    $sql_products = "SELECT p.pid, p.pname, p.psellprice, pr.size, pr.color, pr.quantity, pr.itemprice
                     FROM product_receipt pr
                     JOIN product p ON pr.pid = p.pid
                     WHERE pr.rid = ?";
                     
    $stmt_products = $connect->prepare($sql_products); // Đã thay đổi từ $conn thành $connect
    $stmt_products->bind_param("s", $receiptId);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();
                     
    $products = [];
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
                 
    return [
        'receipt' => $receipt,
        'products' => $products
    ];
}

// Get receipt ID from URL
$receiptId = $_REQUEST['rid'] ?? 'default_rid';
$details = getReceiptDetails($receiptId);
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set timezone to Vietnam
$printDate = date('Y-m-d H:i:s');

// Check customer information
$customerName = !empty($details['receipt']['cname']) ? htmlspecialchars($details['receipt']['cname']) : '#####';
$customerPhone = !empty($details['receipt']['cphone']) ? htmlspecialchars($details['receipt']['cphone']) : '#####';
$customerEmail = !empty($details['receipt']['cemail']) ? htmlspecialchars($details['receipt']['cemail']) : '#####';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="../css/inhoadon.css" media="all" />
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    <style>
        .image-center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 30%; /* Adjust as needed */
            height: 30%;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="logo.jpg">
    </div>
    <h1>INVOICE #<?php echo htmlspecialchars($details['receipt']['rid']); ?></h1>
    <div id="company" class="clearfix">
        <div>ANH THUY STORE</div>
        <div>Triều Khúc,<br /> HN, VN</div>
        <div>0326918392</div>
        <div><a href="mailto:anhdat26102002@gmail.com">anhdat26102002@gmail.com</a></div>
    </div>
    <div id="project">
        <div><span>Tên</span> <?php echo $customerName; ?></div>
        <div><span>Ngày tạo</span> <?php echo htmlspecialchars($details['receipt']['rdate']); ?></div>
        <div><span>SDT</span> <?php echo $customerPhone; ?></div>
        <div><span>Email</span> <?php echo $customerEmail; ?></div>
        <div><span>Ngày in</span> <?php echo $printDate; ?></div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th class="service">STT</th>
            <th class="desc">Tên sản phẩm</th>
            <th>Size</th>
            <th>Màu sắc</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        foreach ($details['products'] as $product) {
            echo "<tr>";
            echo "<td class='service'>" . $i++ . "</td>";
            echo "<td class='desc'>" . htmlspecialchars($product['pname']) . "</td>";
            echo "<td class='unit'>" . htmlspecialchars($product['size']) . "</td>";
            echo "<td class='unit'>" . htmlspecialchars($product['color']) . "</td>";
            echo "<td class='unit'>" . number_format(htmlspecialchars($product['itemprice'] / $product['quantity'])) . "</td>";
            echo "<td class='qty'>" . htmlspecialchars($product['quantity']) . "</td>";
            echo "<td class='total'>" . number_format(htmlspecialchars($product['itemprice'])) . "</td>";
            echo "</tr>";
        }
        ?>
        
        <tr>
    <td colspan="6">Giảm giá (Voucher)</td>
    <td class="total">
        -<?php 
            echo number_format(
                !empty($details['receipt']['vpercent']) ? htmlspecialchars($details['receipt']['vpercent']) : 0
            ); 
        ?>%
    </td>
</tr>
        <tr>
            <td colspan="6" class="grand total">Tổng tiền thanh toán</td>
            <td class="grand total"><?php echo number_format(htmlspecialchars($details['receipt']['rmoney'])); ?></td>
        </tr>
        </tbody>
    </table>
      </br>
      </br>
      </br>
    <div id="notices">
        <div class="notice" style="text-align: center; font-size: 20px;">Vui lòng quét mã QR dưới đây để thanh toán, xin cảm ơn!</div>
    </div>
      </br>
    <div>
        <img src="qr_anh.jpg" alt="Descriptive Alt Text" class="image-center">
    </div>
</main>
<footer>
    &copy; 2024 LOVE SHOP. All rights reserved.
</footer>
</body>
</html>