<?php

include("../../db/MySQLConnect.php");

// Kiểm tra đăng nhập và vai trò của người dùng (ví dụ: chỉ admin mới có quyền quản lý voucher)
if (!isset($_SESSION["feedback_error"])){
    $_SESSION["feedback_error"]="";
}


$query = "SELECT * FROM feedback";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bình luận</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/Product-Management.css">
   
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Quản lý bình luận</h2>
    <font color=red><?php echo $_SESSION["feedback_error"]; ?></font><br>
    <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
        <thead>
            <tr>
                <th scope="col">Mã bình luận</th>
                <th scope="col">Nội dung bình luận</th>
                <th scope="col">Sao</th>
                <th scope="col">Ảnh/Video</th>
                <th scope="col">Ngày bình luận</th>
                <th scope="col">Mã hóa đơn</th>
                <th scope="col">Ẩn</th>
            </tr>
        </thead>
        <tbody>
            
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['fid']}</td>";
            echo "<td>{$row['fcontent']}</td>";
            echo "<td>{$row['fstar']}</td>";
            $mediaPath = "../../../User/UserManagement (1)/uploads/".$row['fmedia'];
            if (pathinfo($mediaPath, PATHINFO_EXTENSION) == 'mp4') {
                echo '<td><video width="320" height="240" controls>';
                echo '<source src="' . $mediaPath . '" type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video></td>';
            } else {
                echo '<td><img src="' . $mediaPath . '" alt="Feedback Media"></td>';
            }
            echo "<td>{$row['fdate']}</td>";

            echo "<td>{$row['id']}</td>";


            echo '<td><button type="button" class="btn btn-danger"><a class="button-delete" onclick="return confirm(\'Bạn có chắc muốn ẩn bình luận "' . $row["fid"] . '" không?\')" href="Feedback_hide.php?fid=' . $row["fid"] . '">Ẩn bình luận</a></button></td>';
            echo "</tr>";
        }
        ?>
        </tbody>
       
    </table>
    
    
</body>

</html>

<?php
unset($_SESSION["feedback_error"]);
// Đóng kết nối
$connect->close();
?>