<?php

include("../../db/MySQLConnect.php");
$pid = $_GET["pid"];

$sql = "select * from product where pid=" . $pid;
$result = $connect->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Chỉnh sửa sản phẩm</h2>
    <form class="row g-3" action="Product_edit_action.php?pid=<?php echo $pid ?>" method="POST" enctype="multipart/form-data" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;">
        <div class="col-md-12">
            <label for="pname" class="form-label">Tên sản phẩm:</label>
            <input type="text" class="form-control" id="pname" name="txtpname" value="<?php echo $row['pname'] ?>" required>
        </div>
        <div class="mb-12">
            <label for="pdesc" class="form-label">Mô tả:</label>
            <textarea class="form-control" name="txtpdesc" id="pdesc" rows="3" ><?php echo $row['pdesc'] ?></textarea>
        </div>
        <div class="col-md-6">
            <label for="poriginalprice" class="form-label">Giá Niêm Yết:</label>
            <input type="number" name="txtporiginalprice" class="form-control" min="1000" max="999999999" value="<?php echo $row['poriginalprice'] ?>" required>
        </div>
        <div class="col-md-6">
            <label for="psellprice" class="form-label">Giá Bán:</label>
            <input type="number" name="txtpsellprice" class="form-control" min="1000" max="999999999" value="<?php echo $row['psellprice'] ?>" required>
        </div>

        <div class="col-md-12">
            <label for="pquantity" class="form-label">Số lượng:</label>
            <input type="number" name="txtpquantity" class="form-control" min="0" max="999999" value="<?php echo $row['pquantity'] ?>" required>
        </div>
       
        <div class="col-md-12">
            <img src="../../../User/UserManagement (1)/uploads/<?php echo $row['pimage'] ?>" alt="Product Image" style="width: 100px; height: auto;">
            <br>
            <label for="" class="form-label">Chọn ảnh khác:</label>
            <input type="file" class="form-control" name="txtpimage" accept="image/*" placeholder="chọn ảnh khác">
        </div>

        <div class="col-4">
            <div class="form-check">
                <label class="" for="categories">
                    Danh mục sản phẩm:
                </label>
                <div class="">
                    <?php

                    if (!isset($_SESSION["product_edit_error"])) {
                        $_SESSION["product_edit_error"] = "";
                    }

                    include("../../db/MySQLConnect.php");
                    $query_categories = "SELECT * FROM categories";
                    $result_categories = $connect->query($query_categories);

                    // Hiển thị checkbox cho mỗi size
                    if ($result_categories->num_rows > 0) {
                        while ($row_category = $result_categories->fetch_assoc()) {
                           
                                $cateid = $row_category["cateid"];
                                $catename = $row_category["catename"];
                    
                                // Kiểm tra xem có bản ghi trong bảng product_categories hay không
                                $query_check = "SELECT * FROM product_categories WHERE pid = " . $row['pid'] . " AND cateid = $cateid";
                                $result_check = $connect->query($query_check);
                    
                                $checked = ($result_check && $result_check->num_rows > 0) ? 'checked' : ''; // Kiểm tra và đánh dấu ô checkbox nếu có bản ghi
                    
                                echo '<input type="checkbox" name="categories[]" value="' . $cateid . '" ' . $checked . '>';
                                echo '<label for="' . $cateid . '">' . $catename . '</label>';
                            
                        }
                    }

                    ?>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="form-check">
                <label class="form-check-label" for="sizes">
                    Các size khả dụng:
                </label>
                <div>
                    <?php
                    // Kết nối cơ sở dữ liệu và truy vấn bảng 'size' để lấy danh sách size
                    // Lưu ý: Phần kết nối cơ sở dữ liệu và truy vấn phải được thực hiện ở trên đây
                    $query_sizes = "SELECT * FROM size";
                    $result_sizes = $connect->query($query_sizes);

                    // Hiển thị checkbox cho mỗi size
                    if ($result_sizes->num_rows > 0) {
                        while ($row_size = $result_sizes->fetch_assoc()) {
                            $sizeid = $row_size["sizeid"];
                            $sizename = $row_size["sizename"];
                
                            $query_check = "SELECT * FROM product_size WHERE pid = " . $row['pid'] . " AND sizeid = $sizeid";
                            $result_check = $connect->query($query_check);
                
                            $checked = ($result_check && $result_check->num_rows > 0) ? 'checked' : ''; // Kiểm tra và đánh dấu ô checkbox nếu có bản ghi
                
                            echo '<input type="checkbox" name="sizes[]" value="' . $sizeid . '" ' . $checked . '>';
                            echo '<label for="' . $sizeid . '">' . $sizename . '</label>';
                        }
                    }

                    ?>
                </div>

            </div>
        </div>

        <div class="col-4">
            <div class="form-check">
                <label class="form-check-label" for="sizes">
                    Các màu khả dụng:
                </label>
                <div>
                    <?php
                    $query_colors = "SELECT * FROM color";
                    $result_colors = $connect->query($query_colors);
                    // Hiển thị checkbox cho mỗi màu
                    if ($result_colors->num_rows > 0) {
                        while ($row_color = $result_colors->fetch_assoc()) {
                          
                                $colorid = $row_color["colorid"];
                                $colorname = $row_color["colorname"];
                    
                                // Kiểm tra xem có bản ghi trong bảng product_categories hay không
                                $query_check = "SELECT * FROM product_color WHERE pid = " . $row['pid'] . " AND colorid = $colorid";
                                $result_check = $connect->query($query_check);
                    
                                $checked = ($result_check && $result_check->num_rows > 0) ? 'checked' : ''; // Kiểm tra và đánh dấu ô checkbox nếu có bản ghi
                    
                                echo '<input type="checkbox" name="colors[]" value="' . $colorid . '" ' . $checked . '>';
                                echo '<label for="' . $colorid . '">' . $colorname . '</label>';
                            
                        }
                    }

                    ?>
                </div>

            </div>
        </div>
        <!-- Check trạng thái -->
        <?php
        $sql_get_status = "SELECT pstatus FROM product WHERE pid = $pid";
        $result_status = $connect->query($sql_get_status);
        $pstatus = 0;
        if ($result_status->num_rows > 0) {
            $row_status = $result_status->fetch_assoc();
            $pstatus = $row_status['pstatus'];
        } else {
            // Xử lý nếu không tìm thấy trạng thái cho pid cụ thể
            $pstatus = 0; // Giá trị mặc định hoặc xử lý theo ý của bạn
        }
        ?>
        <div class="col-md-12">
            <label for="pstatus" class="form-label">Trạng thái</label>
            <select class="form-select" id="pstatus" name="txtpstatus" required>
                <option value="1" <?php echo ($pstatus == 1) ? 'selected' : ''; ?> >Khả dụng</option>
                <option value="0" <?php echo ($pstatus == 0) ? 'selected' : ''; ?> >Không khả dụng</option>
            </select>
        </div>

        <div class="col-md-12">
            <?php
                $sql="Select * from sale";
                $result = $connect->query($sql) or die($conn->error);

            ?>
            <label for="pstatus" class="form-label">Chương trình khuyến mãi áp dụng</label>
            <select class="form-select" id="psale" name="txtpsale" required>
                <option value="none">Không có chương trình</option>
                <?php
                while($row2 = $result->fetch_assoc()) {
                    
                    $selected = ($row2["saleid"] == $row["saleid"]) ? 'selected' : '';

                    echo '  <option value="' . $row2['saleid'] . '" ' . $selected . '>' . $row2['salename'] . '</option>';

                }
                ?>
            </select>
        </div>

        <font color=red><?php echo $_SESSION["product_edit_error"]; ?></font><br>

        <div class="col-12">
            <button class="btn btn-primary" type="reset">Reset</button>
            <button class="btn btn-primary" type="submit">Sửa sản phẩm</button>
        </div>
    </form>

   
</body>

<?php
unset($_SESSION["product_edit_error"]);
?>

</html>