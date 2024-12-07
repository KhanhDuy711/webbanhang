<?php

require("../../db/connect.php");
session_start();
	if (!isset($_SESSION["color_error"])){
		$_SESSION["color_error"]="";
	}

if (!empty($_POST['txtcolorname']) ) {
    $colorname = $_POST['txtcolorname'];

    $sql = "SELECT * FROM color WHERE colorname = '$colorname'";
    $result = $conn->query($sql) or die($conn->connect_error);

    if ($result->num_rows > 0) {
        $_SESSION["color_error"] = "Màu \"$colorname\" đã tồn tại!";
    } else {
        $sqlinsert = "INSERT INTO color(colorname) VALUES ('$colorname')";
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION["color_error"] = "Thêm mới màu sắc thành công!";
        } else {
            $_SESSION["color_error"] = "Lỗi khi thêm màu: " . $conn->error;
        }
    }
} else {
    $_SESSION["color_error"] = "Vui lòng điền đầy đủ thông tin!";
    header("Location:index.php?manage=Color_add");
}
header("Location:index.php?manage=color");

?>
