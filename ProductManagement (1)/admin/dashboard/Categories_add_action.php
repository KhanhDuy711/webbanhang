<?php

require("../../db/connect.php");
session_start();
	if (!isset($_SESSION["cate_error"])){
		$_SESSION["cate_error"]="";
	}

if (!empty($_POST['txtcatename']) && !empty($_POST['txtcatedesc'])) {
    $catename = $_POST['txtcatename'];
    $catedesc = $_POST['txtcatedesc'];

    $sql = "SELECT * FROM categories WHERE catename = '$catename'";
    $result = $conn->query($sql) or die($conn->connect_error);

    if ($result->num_rows > 0) {
        $_SESSION["cate_error"] = "Danh mục $catename đã tồn tại!";
    } else {
        $sqlinsert = "INSERT INTO categories (catename, catedesc) VALUES ('$catename', '$catedesc')";
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION["cate_error"] = "Thêm mới danh mục thành công!";
        } else {
            $_SESSION["cate_error"] = "Lỗi khi thêm danh mục: " . $conn->error;
        }
    }
} else {
    $_SESSION["cate_error"] = "Vui lòng điền đầy đủ thông tin!";
}
header("Location:index.php?manage=categories");

?>
