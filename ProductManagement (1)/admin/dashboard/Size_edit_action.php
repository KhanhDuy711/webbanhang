<?php 
	session_start();
    if (!isset($_SESSION["size_error"])) {
        $_SESSION["size_error"] = "";
    }
	require("../../db/connect.php");
	$sizeid = $_REQUEST["sizeid"];
    $sizename = $_POST["txtsizename"];
    $sizedesc = $_POST["txtsizedesc"];
	$sql = "select * from size where sizename='$sizename' and sizeid=$sizeid";
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows>0){
		$_SESSION["size_error"]="Size $sizename đã tồn tại!";
		header("Location:Size_edit.php?sizeid=$sizeid");
	} else {
		$sql_update="update size set 
						sizename='$sizename',
						sizedesc='$sizedesc'
						where sizeid=$sizeid";
		$conn->query($sql_update) or die($conn->error);
		$conn->close();
		$_SESSION["size_error"]="Chỉnh sửa thành công!";
		header("Location:index.php?manage=size");
	}
?>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	</body>
</html>