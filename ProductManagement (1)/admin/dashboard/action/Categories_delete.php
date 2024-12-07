<?php 
	session_start();
	require("../../../db/Connect.php");
	$cateid = $_REQUEST["cateid"];
	$sql = "update categories set catestatus = 0 where cateid=".$cateid;
	$conn->query($sql) or die($conn->error);
	$conn->close();
	$_SESSION["cate_error"]="Xóa thành công!";
	header("Location:../index.php?manage=categories");
?>