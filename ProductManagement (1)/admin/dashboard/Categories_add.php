<?php

if (!isset($_SESSION["cate_error"])) {
    $_SESSION["cate_error"] = "";
}
include "../../db/connect.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm danh mục</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="margin-left:100px;background-color: #f1efef">
  <h2 style="margin-top:10px">Thêm danh mục mới</h2>
  <center>
    <font color=red><?php echo $_SESSION["cate_error"]; ?></font>
  </center>
  <form class="row g-3"
    style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;" method="POST"
    action="Categories_add_action.php">
    <div class="col-md-12">
      <label for="catename" class="form-label">Tên danh mục:</label>
      <input type="text" class="form-control" id="catename" name="txtcatename"  required>
    </div>
    <div class="mb-12">
      <label for="catedesc" class="form-label">Mô tả:</label>
      <textarea class="form-control" name="txtcatedesc" id="catedesc" rows="3" ></textarea>
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="reset">Reset</button>
      <button class="btn btn-primary" type="submit">Thêm danh mục</button>
    </div>
  </form>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
unset($_SESSION["cate_error"]);
?>