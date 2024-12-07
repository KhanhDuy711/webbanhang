<?php

require("../../db/connect.php");
session_start();
	if (!isset($_SESSION["sale_error"])){
		$_SESSION["sale_error"]="";
	}
    
    $saleid = $_REQUEST['saleid'];

    $salename = $_POST['txtsalename'];
    $salepercent = $_POST['txtsalepercent'];
    $salestart = $_POST['txtsalestart'];
    $saleend = $_POST['txtsaleend'];

    $date1 = new DateTime($salestart);
    $date2 = new DateTime($saleend);
    if($date1>$date2) 
    {
        $_SESSION["sale_error"] = "Ngày bắt đầu phải có trước ngày kết thúc!";
        header("Location:index.php?manage=Sale_edit&saleid=$saleid");
        exit();
    }

    $sql = "SELECT * FROM sale WHERE salename = '$salename'";
    $result = $conn->query($sql) or die($conn->connect_error);
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0 && $row['salename'] != $salename) {
        $_SESSION["sale_error"] = "Chương trình ".$salename." đã tồn tại!";
    } else {
        $sqlinsert = "Update sale set 
                        salename = '".$salename."',
                        salepercent = '".$salepercent."',
                        salebegin = '".$salestart."',
                        saleend = '".$saleend."'
                        where saleid = ".$saleid;
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION["sale_error"] = "Sửa chương trình thành công!";
        } else {
            $_SESSION["sale_error"] = "Lỗi khi sửa chương trình: " . $conn->error;
        }
    }

header("Location:index.php?manage=sale");

?>
