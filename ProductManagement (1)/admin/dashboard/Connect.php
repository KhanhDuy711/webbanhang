<?php 
$servername="localhost:3306";
$username="root";
$password="";
$database="do_an_web";
$connect  = new mysqli($servername,$username,$password,$database);
if ($connect ->connect_error){
	die("Lỗi kết nối với CSDL");
}
?>