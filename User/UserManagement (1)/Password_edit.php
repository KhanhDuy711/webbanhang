<?php 
	session_start();
	if (!isset($_SESSION["Password_edit_error"])){
		$_SESSION["Password_edit_error"]="";
	}
	$cid =$_REQUEST["cid"];
	$sql = "select * from customer where cid = ".$cid;
	require("../connect.php");
	$result = $conn->query($sql) or die($conn->error);
	if ($result->num_rows==0){
		$_SESSION["Password_edit_error"]="Data not exist!";
		header("Location:UserManagement (1)/CustomerDetail.php?cid=$cid");
	} else {
		$row = $result->fetch_assoc();
?>
<html>
	<head>
		<meta charset="utf-8">
		
	</head>
	<body>
		<h1 align=center>Đổi mật khẩu</h1>
		<center>
			<font color=red><?php echo $_SESSION["Password_edit_error"];?></font>
		</center>
		<form method=POST action="Password_edit_action.php?cid=<?php echo $cid;?>">
			<table border=0 align=center cellspacing=10>
				<tr>
					<td align=right>Mật khẩu cũ: </td>
                    <td><input type="text" name="txtOldpassword" ></td>

				<tr>
				<tr>
					<td align=right>Mật khẩu mới:</td>
                    <td><input type="text" name="txtNewpassword1" ></td>

				</tr>
				<tr>
                    <td align=right>Nhập lại mật khẩu mới: </td>
                    <td><input type="text" name="txtNewpassword2" ></td>
                </tr>
				<tr>
					<td align=right><input type=reset>
					<td><input type=submit value="Đổi mật khẩu"></td>
				</tr>
			</table>
		</form>


        
	</body>
</html>
<?php 
	}
	$conn->close();
	unset($_SESSION["Password_edit_error"]);
?>