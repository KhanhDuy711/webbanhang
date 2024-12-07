<?php
    session_start();
    $cname=$_POST["username"];
    $caddress = $_POST["address"];
    $cphone = $_POST["phone"];
    $cemail = $_POST["email"];
    $caccount = $_POST["account"];
    $cpassword = $_POST["password"];

    require_once("connect.php");
    $sql = "SELECT * FROM customer WHERE caccount = '".$caccount."'";
    $result = $conn->query($sql) or die ($conn->connect_error);

    
    if($result->num_rows>0){
        $_SESSION["register_error"]="Tài khoản đã tồn tại, vui lòng đăng ký tên đăng nhập khác khác!";
		header("Location:./register.php");
    }
    else{
        $_SESSION["register_error"]="";
        $sql = "insert into customer(cname, caddress, cphone,cemail,caccount,cpassword) values('".$cname."','".$caddress."','".$cphone."','".$cemail."','".$caccount."','".$cpassword."')";
        $conn->query($sql) or die($conn->error);
        if($conn->error==""){
            $_SESSION["register_error"] = "Register successfull!";
            header("Location:../index.php");
        } else {
            $_SESSION["register_error"]="Error insert data";
            header("Location:./register.php");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    
</body>
</html>