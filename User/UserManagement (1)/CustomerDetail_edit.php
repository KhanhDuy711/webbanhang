<?php 
	session_start();
	if (!isset($_SESSION["CustomerDetail_edit_error"])){
		$_SESSION["CustomerDetail_edit_error"]=" ";
	}
	$cid =$_REQUEST["cid"];
	$sql = "select * from customer where cid = ".$cid;
	require("../connect.php");
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows==0){
		$_SESSION["CustomerDetail_eidt_error"]="Data not exist!";
		header("Location:UserManagement (1)/CustomerDetail.php?cid=$cid");
	} else {
		$row = $result->fetch_assoc();
?>
<html>
	<head>
		<meta charset="utf-8">
		
	</head>
	<body>
		<h1 align=center>Chỉnh sửa thông tin cá nhân</h1>
		<center>
			<font color=red><?php echo $_SESSION["CustomerDetail_edit_error"];?></font>
		</center>
		<form method=POST action="UserManagement (1)/CustomerDetail_edit_action.php?cid=<?php echo $cid;?>">
			<table border=0 align=center cellspacing=10>
				<tr>
					<td align=right>Địa chỉ: </td>
					<td><textarea rows=10 name=txtCaddress><?php echo $row["caddress"]?></textarea></td>
				<tr>
				<tr>
					<td align=right>Số điện thoại:</td>
					<td><input type="number" name="numCphone" value="<?php echo $row["cphone"]?>"></input></td>
				</tr>
				<tr>
                    <td align=right>Email: </td>
                    <td><input type="email" name="txtCemail" value="<?php echo $row["cemail"] ?>" ></td>
                </tr>
				<tr>
					<td align=right><input type=reset>
					<td><input type=submit value="Cập nhật"></td>
				</tr>
			</table>
		</form>


        
	</body>
</html>
<?php 
	}
	$conn->close();
	unset($_SESSION["CustomerDetail_edit_error"]);
?>