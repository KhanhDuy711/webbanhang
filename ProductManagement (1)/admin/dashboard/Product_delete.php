<?php
require("../../db/Connect.php");
    $pid = $_REQUEST["pid"];
    session_start();
    $sql = "Update product set pstatus = 0 where pid=".$pid;
    $conn->query($sql) or die($conn->error);
    $_SESSION["product_edit_error"]="Xóa sản phẩm thành công!";
    header("Location:index.php?manage=product");

?>