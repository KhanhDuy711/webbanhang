<?php 
	session_start();
	require("../../db/connect.php");
	$colorid = $_REQUEST["colorid"];
	$sql = "delete from color where colorid=$colorid";
	$conn->query($sql) or die($conn->error);
	$conn->close();
	$_SESSION["color_error"]="Xóa thành công!";
	header("Location:index.php?manage=color");
?>