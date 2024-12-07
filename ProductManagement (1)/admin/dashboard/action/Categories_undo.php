<?php
    $cateid = $_REQUEST["cateid"];
    session_start();
    require("../../../db/Connect.php");

    $sql = "Update categories set catestatus = 1 where cateid=".$cateid;
    $conn->query($sql) or die($conn->error);
    $_SESSION["cate_error"]="Khôi phục danh mục thành công!";
    header("Location:../index.php?manage=Categories_trashcan");

?>