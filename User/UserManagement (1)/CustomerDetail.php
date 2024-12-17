<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/user.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Thông tin cá nhân</title>
</head>

<body>
    <?php include_once("header_da_2.php")?>
    <?php

    if (!isset($_SESSION["CustomerDetail_edit_error"])) {
        $_SESSION["CustomerDetail_edit_error"] = " ";
    }
    if (!isset($_SESSION["Password_edit_error"])) {
        $_SESSION["Password_edit_error"] = " ";
    }
    ?>



    <?php include("navigation_da_2.php"); ?>
    <center>
        <font color=red><?php echo $_SESSION["CustomerDetail_edit_error"]; ?></font>
    </center>
    <center>
        <font color=red><?php echo $_SESSION["Password_edit_error"]; ?></font>
    </center>
    <?php
    include '../connect.php';

    $sql = "SELECT * from customer where cid = ". $customer_id ;
    $result = $conn->query($sql);

    ?>

    <br>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>


            <?php
        }
    } else {
        echo "<tr><td colspan='3'>Không có thông tin khách hàng.</td></tr>";
    }


    ?>


    <?php
    $sql = "select * from customer where cid=" . $customer_id;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <div class="content">
        <div class="user-content row">
            <div class="side-menu col-md-3 sol-sm-12">
                <div class="username ">
                    <i class="far fa-user-circle font "></i>
                    Tài khoản của <br> <span class="font" style="padding-left: 22%;">
                        <?php echo $row["cname"] ?>
                    </span>
                </div>
                <div class="submenu">
                    <ul>
                        <li class="subc font">
                            <a class="btn-custom" onclick="document.getElementById('id01').style.display='block'"
                                href="#">Chỉnh sửa thông tin cá nhân</a>
                        </li>
                        <li class="subc font">
                            <a class="btn-custom" onclick="document.getElementById('id02').style.display='block'"
                                href="#">Đổi mật khẩu</a><br>
                        </li>
                        <li class="subc font">
                            <a class="btn-custom" onclick="document.getElementById('donhang').style.display='block'"
                                href="#history">Đơn hàng của tôi</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="information col-md-9 sol-sm-12">
                <div class="information-user">
                    <h5 class="font title-infor">THÔNG TIN CỦA TÔI </h5>
                    <p class="font"><span class="font title-infor">Họ Và Tên:</span>
                        <?php echo $row['cname'] ?>
                    </p>
                    <p class="font"><span class="font title-infor">Địa Chỉ:</span>
                        <?php echo $row['caddress'] ?>
                    </p>
                    <p class="font"><span class="font title-infor">Điện Thoại:</span>
                        <?php echo $row['cphone'] ?>
                    </p>
                    <p class="font"><span class="font title-infor ">Email:</span>
                        <?php echo $row['cemail'] ?>
                    </p>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>


            </div>
        </div>




        <!-- Modal chỉnh sửa thông tin cá nhân -->
        <div id="id01" class="modal">

            <form class="modal-content animate" action="CustomerDetail_edit_action.php?cid=<?php echo $row['cid'] ?>"
                method="post">
                <div class="imgcontainer">
                    <span onclick="document.getElementById('id01').style.display='none'" class="close"
                        title="Close Modal">&times;</span>
                </div>

                <div class="input-content">


                    <label for="address"><b>Địa Chỉ:</b></label>
                    <input class="login-input" type="text" value="<?php echo $row['caddress'] ?>" name="txtCaddress"
                        id="psw" required>


                    <label for="phone"><b>Số Điện Thoại:</b></label>
                    <input class="login-input" type="text" value="<?php echo $row['cphone'] ?>" name="numCphone"
                        id="psw" required>

                    <label for="gender"><b>Email:</b></label>
                    <input class="login-input" type="text" value="<?php echo $row['cemail'] ?>" name="txtCemail"
                        id="psw" required>



                </div>
                <div class="group-button row">
                    <div class="col-md-2 col-sm-0"></div>
                    <button type="button" class="btn btn-outline-danger col-md-3"
                        onclick="document.getElementById('id01').style.display='none'">Huỷ</button>
                    <div class="col-md-2 col-sm-0"></div>
                    <button type="submit" class="btn btn-success col-md-3">Cập Nhật</button>
                    <div class="col-md-2 col-sm-0"></div>
                </div>
            </form>
        </div>

        <!-- Modal chỉnh sửa mật khẩu -->
        <div id="id02" class="modal">

            <form class="modal-content animate" action="Password_edit_action.php?cid=<?php echo $row['cid'] ?>"
                method="post">
                <div class="imgcontainer">
                    <span onclick="document.getElementById('id02').style.display='none'" class="close"
                        title="Close Modal">&times;</span>
                </div>

                <div class="input-content">
                    <label for="address"><b>Mật khẩu cũ:</b></label>
                    <input class="login-input" type="text" name="txtOldpassword" id="psw" required>

                    <label for="address"><b>Mật khẩu mới:</b></label>
                    <input class="login-input" type="text" name="txtNewpassword1" id="psw" required>

                    <label for="address"><b>Xác nhận mật khẩu mới:</b></label>
                    <input class="login-input" type="text" name="txtNewpassword2" id="psw" required>
                </div>
                <div class="group-button row">
                    <div class="col-md-2 col-sm-0"></div>
                    <button type="button" class="btn btn-outline-danger col-md-3"
                        onclick="document.getElementById('id02').style.display='none'">Huỷ</button>
                    <div class="col-md-2 col-sm-0"></div>
                    <button type="submit" class="btn btn-success col-md-3">Cập Nhật</button>
                    <div class="col-md-2 col-sm-0"></div>
                </div>
            </form>
        </div>

        <!-- Đơn hàng của tôi -->
        <div id="history" class="container-fluid table-order  ">
            <br>
            <div class="table-responsive-md" id="donhang" style="display: none; margin: 0 5%;">
                <p class="font" style="font-weight: bold; font-size: large;">Các Đơn Hàng Đã Đặt:</p>
                <?php
                $customerID = $_SESSION['cid'];

                // Fetch order history for the customer, excluding orders with status "Hủy đơn"
                $query = "SELECT a.*FROM receipt a
                                        WHERE cid = ? AND a.rstatus != 'Hủy đơn'";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $customerID);
                $stmt->execute();
                $result33 = $stmt->get_result();

                if ($result33->num_rows > 0) {
                    echo '<table class="table table-hover  table-bordered" >';
                    echo '<thead class="thead-dark">';
                    echo '<tr><th class="tr">Order id</th>
                                            <th  class="tr">Order date</th>
                                            <th  class="tr">Total amount</th>
                                            <th  class="tr">Status</th>
                                            <th  class="tr">Action</th>
                                    </tr>';

                    echo '</thead>';
                    while ($row = $result33->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><a href="#orderDetailsContainer" class="orderDetailsLink" data-rid="' . $row['rid'] . '">' . $row['rid'] . '</a></td>';
                        echo '<td>' . $row['rdate'] . '</td>';
                        echo '<td>' . $row['rmoney'] . '</td>';
                        echo '<td>' . $row['rstatus'] . '</td>';

                        // Check order status (case-insensitive) and display the appropriate action
                        if (strcasecmp($row['rstatus'], 'Đang xử lý') === 0) {
                            echo '<td><a href="cancel_order.php?rid=' . $row['rid'] . '">Cancel order</a></td>';
                        } else {
                            echo '<td>Cannot cancel</td>';
                        }

                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo 'No orders found.';
                }
                ?>
                <br>
            </div>
        </div>
    </div>


    <!-- Bảng chi tiết hóa đơn -->
    <div id="orderDetailsContainer" class="container-fluid table-order " style="width: 90%;margin-left: 75px;"
        align=center>


    </div>

    <br><br><br>
    <!-- Footer -->
    <div class="container-fluid index" id="index">
        <footer>
            <?php include_once("footer.php"); ?>
        </footer>
    </div>

    <?php
    $conn->close();
    unset($_SESSION["CustomerDetail_edit_error"]);
    unset($_SESSION["Password_edit_error"]);
    ?>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Attach a click event to the order details links
        $('.orderDetailsLink').on('click', function (e) {
            e.preventDefault();

            // Get the order ID from the data-rid attribute
            var rid = $(this).data('rid');

            // Make an AJAX request to fetch the order details
            $.ajax({
                type: 'GET',
                url: 'Order_history_detail.php', // Adjust the URL if needed
                data: { rid: rid },
                success: function (data) {
                    // Update the orderDetailsContainer with the fetched data
                    $('#orderDetailsContainer').html(data);
                },
                error: function () {
                    alert('Error loading order details.');
                }
            });
        });
    });

</script>


</html>