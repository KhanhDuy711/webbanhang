<?php
session_start();
$pid = $_GET["pid"];
$quantity = $_POST["quantity"];
$color = $_POST["color"];
$size = $_POST["size"];
if($color==""||$size==""){
	$_SESSION["detail_error"]="Cần chọn size hoặc màu sắc cho sản phẩm muốn thêm vào giỏ hàng";
	header("location:/detail.php?pid=".$pid);
}
require_once("connect.php");
$sql = "select *from product where pid = '" . $pid . "'";
$result = $conn->query($sql) or die($conn->error);
$row = $result->fetch_assoc();

if (!empty($_POST["quantity"]) && !empty($_POST["size"]) && !empty($_POST["color"])) {

	$product = $conn->query("select * from product where pid=" . $pid) or die($conn->error);
	$r[] = $product->fetch_assoc();
	$itemArray = array($r[0]["code"] => array("pid" => $r[0]["pid"], "pname" => $r[0]["pname"], "code" => $r[0]["code"], "pprice" => $r[0]["psellprice"], "pquantity" => $_POST["quantity"], "size" => $_POST["size"], "color" => $_POST["color"], "pimage" => $r[0]["pimage"]));
	// var_dump($itemArray);
	/*
				 "a3"=>("pid"=>3,"pname"=>"xxxx","code"=>"a3"....),
				 "a4"=>("pid"=>4,"pname"=>"xxx","code"=>"a4".....).....
			 */
	//cart đã có hàng trong giỏ
	$check = false;
	if (!empty($_SESSION["cart_item"])) {
		//kiểm tra xem $r[0]["code"] có trong mảng keys của $_SESSION không
		if (in_array($r[0]["code"], array_keys($_SESSION["cart_item"]))) {
			//Nếu có lặp và kiểm tra foreach($age as $x => $val) {}// cấu trúc foeach
			foreach ($_SESSION["cart_item"] as $k => $v) {
				if ($r[0]["pname"] == $_SESSION["cart_item"][$k]["pname"] && $color == $_SESSION["cart_item"][$k]["color"] && $size == $_SESSION["cart_item"][$k]["size"]) { //nếu mà tên thêm vào giỏ mà bằng $k => check color,size xem có trùng không	
					//Thay đổi số lượng trong session của k
					$_SESSION["cart_item"][$k]["pquantity"] += $_POST["quantity"];
					$check = true;
					break;
				}

			}
			// trường hợp trùng tên nhưng khác size or color
			if ($check==false) {
				$newKey = uniqid();
				$_SESSION["cart_item"][$newKey] = $itemArray[$r[0]["code"]];
			}
			$_SESSION["detail_error"]="Thêm vào giỏ hàng thành công";
			header("location:detail.php?pid=" . $pid);
		} else {
			//Nếu không thêm mới 1 mảng sản phẩm vào mảng đã có
			$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
			//array_merge(hợp 2 mảng thành 1 mảng)
			$_SESSION["detail_error"]="Thêm vào giỏ hàng thành công";
			header("location:detail.php?pid=" . $pid);
		}

	} else {
		//nếu chưa có trong giỏ thì thêm mới 1 mảng vào session
		$_SESSION["cart_item"] = $itemArray;
		$_SESSION["detail_error"]="Thêm vào giỏ hàng thành công";
		header("location:detail.php?pid=" . $pid);
	}
} else
	header("location:detail.php?pid=" . $pid)

		?>

	<html>

	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>

	</html>