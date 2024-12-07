<?php
require("../../db/MySQLConnect.php");
session_start();
    if(isset($_POST) && !empty($_POST) ){
        $name=$_POST['name'];
        $password=$_POST['password'];
       // $password=md5($password);
       
       $query = "SELECT aaccount, apassword FROM `admin` GROUP BY aaccount, apassword";

        $result=mysqli_query($connect,$query);
        $check=false;
        while($data=mysqli_fetch_array($result) ){
                if($data['aaccount']==$name && $data['apassword']==$password) {
                    $check=true; $name=$data['aaccount'];
                }
                
        }        
        if( $check==true ){
			$_SESSION['admin_name']=$name;
			$_SESSION['admin_login']=true;
            echo 1;
            exit();
		}
        else  {
			$_SESSION['admin_login']=false;
            echo 0;
            exit();
		}
    }


?>