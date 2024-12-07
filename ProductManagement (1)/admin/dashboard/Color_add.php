<?php

if (!isset($_SESSION["color_error"])) {
    $_SESSION["color_error"] = "";
}
include "../../db/connect.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm màu sắc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Thêm màu sắc mới</h2>
    <center>
        <font color=red><?php echo $_SESSION["color_error"]; ?></font>
    </center>
    <form class="row g-3"  enctype="multipart/form-data" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;" method="POST" action="Color_add_action.php">
        <div class="col-md-12">
            <label for="colorname" class="form-label">Tên màu:</label>
            <input type="text" class="form-control" id="colorname" name="txtcolorname" placeholder="đen" required>
        </div>
    
        <div class="col-12">
            <button class="btn btn-primary" type="reset">Reset</button>
            <button class="btn btn-primary" type="submit">Thêm màu</button>
        </div>
    </form>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
unset($_SESSION["color_error"]);
?>