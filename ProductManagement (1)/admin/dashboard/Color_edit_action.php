<?php 
	session_start();
    if (!isset($_SESSION["color_error"])) {
        $_SESSION["color_error"] = "";
    }
	require("../../db/connect.php");
	$colorid = $_REQUEST["colorid"];
    $colorname = $_POST["txtcolorname"];
	$sql = "select * from color where colorname='$colorname' and colorid=$colorid";
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows>0){
		$_SESSION["color_error"]="Màu \"$colorname\" đã tồn tại!";
		header("Location:index.php?manage=Color_edit&colorid=$colorid");
	} else {
		$sql_update="update color set 
						colorname='$colorname'
						where colorid=$colorid";
		$conn->query($sql_update) or die($conn->error);
		$conn->close();
		$_SESSION["color_error"]="Chỉnh sửa thành công!";
		header("Location:index.php?manage=color");
	}
?>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	</body>
</html>