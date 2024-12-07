<?php
    $pid = $_REQUEST["pid"];
    session_start();
    require("../../db/Connect.php");

    $sql = "Update product set pstatus = 1 where pid=".$pid;
    $conn->query($sql) or die($conn->error);
    $_SESSION["product_edit_error"]="Khôi phục sản phẩm thành công!";
    header("Location:index.php?manage=Product_trashcan");

?>