<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm Sản Phẩm</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="margin-left:100px;background-color: #f1efef">
  <h2 style="margin-top:10px">Thêm Sản Phẩm Mới</h2>
  <form class="row g-3" action="Product_add_action.php" method="POST" enctype="multipart/form-data"
    style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px;border-radius:10px;">
    <div class="col-md-12">
      <label for="pname" class="form-label">Tên sản phẩm:</label>
      <input type="text" class="form-control" id="pname" name="txtpname"  required>
    </div>
    <div class="mb-12">
      <label for="pdesc" class="form-label">Mô tả:</label>
      <textarea class="form-control" name="txtpdesc" id="pdesc" rows="3" placeholder="Description..."></textarea>
    </div>
    <div class="col-md-6">
      <label for="poriginalprice" class="form-label">Giá Niêm Yết:</label>
      <input type="number" name="txtporiginalprice" class="form-control" min="1000" max="999999999" required>
    </div>
    <div class="col-md-6">
      <label for="psellprice" class="form-label">Giá Bán:</label>
      <input type="number" name="txtpsellprice" class="form-control" min="1000" max="999999999" required>
    </div>

    <div class="col-md-6">
      <label for="pquantity" class="form-label">Số lượng:</label>
      <input type="number" name="txtpquantity" class="form-control" min="0" max="999999" required>
    </div>

    <div class="col-md-6">
      <label for="pimage" class="form-label">Ảnh</label>
      <input type="file" class="form-control" name="txtpimage" accept="image/*" required>
    </div>

    <div class="col-4">
      <div class="form-check">
        <label class="" for="categories">
          Danh mục sản phẩm:
        </label>
        <div class="">
          <?php

                    if (!isset($_SESSION["product_add_error"])) {
                        $_SESSION["product_add_error"] = "";
                    }

                    include("../../db/MySQLConnect.php");
                    $query_categories = "SELECT * FROM categories";
                    $result_categories = $connect->query($query_categories);

                    // Hiển thị checkbox cho mỗi size
                    if ($result_categories->num_rows > 0) {
                        while ($row_category = $result_categories->fetch_assoc()) {
                            echo '<input  type="checkbox" name="categories[]" value="' . $row_category["cateid"] . '">';
                            echo '<label for="' . $row_category['cateid'] . '">' . $row_category["catename"] . '</label>';
                        }
                    }

                    ?>
        </div>

      </div>
    </div>

    <div class="col-4">
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
                            echo '<input type="checkbox" name="sizes[]" value="' . $row_size["sizeid"] . '">';
                            echo '<label for="' . $row_size['sizeid'] . '">' . $row_size["sizename"] . '</label>';
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
                            echo '<input type="checkbox" name="colors[]" value="' . $row_color["colorid"] . '">';
                            echo '<label for="' . $row_color['colorid'] . '">' . $row_color["colorname"] . '</label>';
                        }
                    }

                    ?>
        </div>

      </div>
    </div>

    <div class="col-md-12">
      <label for="pstatus" class="form-label">Trạng thái</label>
      <select class="form-select" id="pstatus" name="pstatus" required>
        <option value="1">Khả dụng</option>
        <option value="2">Không khả dụng</option>
      </select>
    </div>

    <font color=red><?php echo $_SESSION["product_add_error"]; ?></font><br>

    <div class="col-12">
      <button class="btn btn-primary" type="reset">Reset</button>
      <button class="btn btn-primary" type="submit">Thêm sản phẩm</button>
    </div>
  </form>

</body>

<?php
unset($_SESSION["product_add_error"]);
?>

</html>