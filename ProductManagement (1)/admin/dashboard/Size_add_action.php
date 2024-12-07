<?php

require("../../db/connect.php");
session_start();
	if (!isset($_SESSION["size_error"])){
		$_SESSION["size_error"]="";
	}

if (!empty($_POST['txtsizename']) && !empty($_POST['txtsizedesc'])) {
    $sizename = $_POST['txtsizename'];
    $sizedesc = $_POST['txtsizedesc'];

    $sql = "SELECT * FROM size WHERE sizename = '$sizename'";
    $result = $conn->query($sql) or die($conn->connect_error);

    if ($result->num_rows > 0) {
        $_SESSION["size_error"] = "Size $sizename đã tồn tại!";
    } else {
        $sqlinsert = "INSERT INTO size (sizename, sizedesc) VALUES ('$sizename', '$sizedesc')";
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION["size_error"] = "Thêm mới size thành công!";
        } else {
            $_SESSION["size_error"] = "Lỗi khi thêm size: " . $conn->error;
        }
    }
} else {
    $_SESSION["size_error"] = "Vui lòng điền đầy đủ thông tin!";
}
header("Location:index.php?manage=size");

?>
