<?php 
$servername="localhost:3306";
$username="root";
$password="";
$database="do_an_web";
$conn = new mysqli($servername,$username,$password,$database);
if ($conn->connect_error){
	die("Lỗi kết nối với CSDL");
}
?>