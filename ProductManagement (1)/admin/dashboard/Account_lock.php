<?php 
	session_start();
	require("../../db/connect.php");
	$cid = $_REQUEST["cid"];

    $query = "SELECT * FROM customer where cid=".$cid;
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $number = ($row['cstatus'] == 0) ? 1 : 0;


	$sql = "update customer set cstatus=".$number." where cid=$cid";
	$conn->query($sql) or die($conn->error);
	$conn->close();
	$_SESSION["account_error"]="Khóa/mở khóa thành công!";
	header("Location:index.php?manage=customer");
?>
