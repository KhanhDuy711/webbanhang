<?php

require("../../db/Connect.php");
session_start();
	if (!isset($_SESSION["sale_error"])){
		$_SESSION["sale_error"]="";
	}


    $salename = $_POST['txtsalename'];
    $salepercent = $_POST['txtsalepercent'];
    $salestart = $_POST['txtsalestart'];
    $saleend = $_POST['txtsaleend'];

    $date1 = new DateTime($salestart);
    $date2 = new DateTime($saleend);
    if($date1>$date2) 
    {
        $_SESSION["sale_error"] = "Ngày bắt đầu phải có trước ngày kết thúc!";
        header("Location:index.php?manage=Sale_add");
        exit();
    }

    $sql = "SELECT * FROM sale WHERE salename = '$salename'";
    $result = $conn->query($sql) or die($conn->connect_error);

    if ($result->num_rows > 0) {
        $_SESSION["sale_error"] = "Chương trình ".$salename." đã tồn tại!";
    } else {
        $sqlinsert = "INSERT INTO sale (salename, salepercent, salebegin, saleend) VALUES ('$salename', $salepercent, '$salestart', '$saleend')";
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION["sale_error"] = "Thêm mới chương trình thành công!";
        } else {
            $_SESSION["cate_error"] = "Lỗi khi thêm chương trình: " . $conn->error;
        }
    }

header("Location:index.php?manage=sale");

?>