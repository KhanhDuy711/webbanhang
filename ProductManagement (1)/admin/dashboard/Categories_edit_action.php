<?php 
	session_start();
    if (!isset($_SESSION["cate_error"])) {
        $_SESSION["cate_error"] = "";
    }
	require("../../db/connect.php");
	$cateid = $_REQUEST["cateid"];
    $catename = $_POST["txtcatename"];
    $catedesc = $_POST["txtcatedesc"];
	$sql = "select * from categories where catename='$catename' and cateid<>$cateid";
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows>0){
		$_SESSION["cate_error"]="Danh mục $catename đã tồn tại!";
		header("Location:index.php?manage=categories");
	} else {
		$sql_update="update categories set 
						catename='$catename',
						catedesc='$catedesc'
						where cateid=$cateid";
		$conn->query($sql_update) or die($conn->error);
		$conn->close();
		$_SESSION["cate_error"]="Chỉnh sửa thành công!";
		header("Location:index.php?manage=categories");
	}
?>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	</body>
</html>