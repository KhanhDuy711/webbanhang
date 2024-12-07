<?php 
	session_start();
	require("../connect.php");
	$cid = $_REQUEST["cid"];
	$caddress = $_POST["txtCaddress"];
    $cphone = $_POST["numCphone"];
    $cemail = $_POST["txtCemail"];
	
	$sql = "select * from customer where cphone='".$cphone."' and cid<>$cid";
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows>0){
		$_SESSION["CustomerDetail_edit_error"]="Số điện thoại bạn muốn thay đổi đã được đăng ký!";
		header("Location:CustomerDetail.php");
	} else {
		$sql_update="update customer set 
						caddress = '$caddress',
                        cphone = '$cphone',
                        cemail = '$cemail'
						where cid=$cid";
		$conn->query($sql_update) or die($conn->error);
		$conn->close();
		$_SESSION["CustomerDetail_edit_error"]="Update success!";
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