<?php 
	session_start();
	require("../../db/Connect.php");
	$saleid = $_REQUEST["saleid"];

    $foreign = "UPDATE product
    SET saleid = NULL
    WHERE saleid = ".$saleid;
    $conn->query($foreign) or die($conn->error);

	$sql = "delete from sale where saleid=$saleid";
	$conn->query($sql) or die($conn->error);
	$conn->close();
	$_SESSION["sale_error"]="Xóa chương trình thành công!";
	header("Location:index.php?manage=sale");
?>