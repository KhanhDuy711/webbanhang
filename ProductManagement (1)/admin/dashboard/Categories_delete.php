<?php 
	session_start();
	require("../../db/MySQLConnect.php");
	$cateid = $_REQUEST["cateid"];
	$sql = "delete from categories where cateid=$cateid";
	$connect->query($sql) or die($conn->error);
	$connect->close();
	$_SESSION["cate_error"]="Xóa thành công!";
	header("Location:index.php?manage=categories");
?>