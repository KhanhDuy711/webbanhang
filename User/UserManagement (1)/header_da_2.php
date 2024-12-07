<header id="header">
  <div class="row">
    <div class=" col-md-5 col-sm-0">
      <h5 style="font-style: italic;font-weight: bold;color: #fff;">Hotline: 0123456789</h5>
    </div>
    <div class="Logo-Brand col-md-2 col-sm-5">
      <img src="images/logo2.png " style="width:100%">
    </div>
    <div class=" col-md-1 col-sm-0"></div>
    <div class="Account col-md-4 col-sm-5">

      <?php 
			if (isset($_SESSION["cid"])) {
				// The customer is logged in
				$customer_id = $_SESSION["cid"];
				$customer_name = $_SESSION["cname"];
				// You can use $customer_id and $customer_name as needed
			}
			?>

      <button class="login btn btn-outline-success">
        <a style="text-decoration: none; color: green; font-weight: bold;" href='<?php
				if (isset($_SESSION['cid'])) {
					if ($_SESSION['cid']) {
						echo "./CustomerDetail.php"; // Link tới trang hiển thị thông tin khách hàng
					} else {
						echo "./Login.php"; // Link tới trang đăng nhập
					}
				} else {
					echo "./Login.php"; // Link tới trang đăng nhập (mặc định)
				}
			?>'>
          <?php
				if (isset($_SESSION['cid'])) {
					if ($_SESSION['cid']) {
						echo '<i class="fas fa-user-alt"></i> ' . $customer_name; // Hiển thị tên khách hàng khi đã đăng nhập
					} else {
						echo "Đăng Nhập"; // Hiển thị khi chưa đăng nhập
					}
				} else {
					echo "Đăng Nhập"; // Hiển thị khi chưa đăng nhập
				}
				?>
        </a>
      </button>
      <button class="btn btn-outline-danger register"><a class="register"
          style="text-decoration: none; color: red;font-weight:bold;" href='<?php
					if (isset($_SESSION['cid']) ) {
						if($_SESSION['cid']) echo "./logout.php";
						else echo "./register.php";
						}
					else echo "./register.php";
				?>'>
          <?php
					if (isset($_SESSION['cid']) ) {
						if($_SESSION['cid']) echo "Đăng Xuất"; 
						  
						else echo "Đăng Ký";
					}
					else echo "Đăng Ký";

				?>
        </a>
      </button>

    </div>

  </div>


</header>