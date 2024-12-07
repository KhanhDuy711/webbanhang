<?php 
	session_start();
	require("../connect.php");
	$cid = $_REQUEST["cid"];
	$old = $_POST["txtOldpassword"];
    $new1 = $_POST["txtNewpassword1"];
    $new2 = $_POST["txtNewpassword2"];
	
	$sql = "select * from customer where cpassword='".$old."' and cid=$cid";
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows==0){
		$_SESSION["Password_edit_error"]="Mật khẩu cũ bạn nhập không đúng!";
		header("Location:CustomerDetail.php");
	} else if($new1 != $new2) {
        $_SESSION["Password_edit_error"]="Mật khẩu bạn nhập lại không trùng khớp!";
		header("Location:CustomerDetail.php");
    }
    else {
		$sql_update="update customer set 
						cpassword = '$new1'
						where cid=$cid";
		$conn->query($sql_update) or die($conn->error);
		$conn->close();
		$_SESSION["Password_edit_error"]="Cập nhật mật khẩu mới thành công!";
		header("Location:CustomerDetail.php");
	}
?>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	</body>
</html>