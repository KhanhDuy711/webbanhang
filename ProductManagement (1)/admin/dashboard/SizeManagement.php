<?php

// Start the session and check for any size-related errors
if (!isset($_SESSION["size_error"])){
    $_SESSION["size_error"] = "";
}

// Include the database connection file
include("../../db/MySQLConnect.php");

// Query to fetch all sizes from the database
$query = "SELECT * FROM size";
$result = $connect->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý size</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
        .button-edit, .button-delete, .button-Add {
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body style="margin-left:100px;background-color: #f1efef">
    <h2 style="margin-top:10px">Quản lý size</h2>
    <center>
        <font color=red><?php echo $_SESSION["size_error"]; ?></font>
    </center>

    <!-- Ô tìm kiếm kích cỡ -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Tìm kiếm size theo tên...">
    </div>

    <!-- Button to add a new size -->
    <button type="button" class="btn btn-primary">
        <a class="button-Add" href="index.php?manage=Size_add">Thêm size</a>
    </button>
    <button type="button" class="btn btn-secondary">
        <a href="Size_print.php" target="_blank">In danh sách size</a>
    </button>

    <!-- Table to display all sizes -->
    <table class="table table-striped" style="background-color:white;border:1px solid #ccc;margin-top:30px;padding:20px">
        <thead>
            <tr>
                <th scope="col">Mã size</th>
                <th scope="col">Tên size</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Sửa</th>
                <th scope="col">Xóa</th>
            </tr>
        </thead>
        <tbody id="sizeTableBody">
            <!-- Loop through each size and display it in a table row -->
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['sizeid']; ?></td>
                    <td><?php echo $row['sizename']; ?></td>
                    <td><?php echo $row['sizedesc']; ?></td>
                    <td>
                        <button type='button' class='btn btn-warning'>
                            <a class='button-edit' href='index.php?manage=Size_edit&sizeid=<?php echo $row['sizeid']; ?>'>Sửa</a>
                        </button>
                    </td>
                    <td>
                        <button type='button' class='btn btn-danger'>
                            <a class='button-delete' onclick="return confirm('Bạn có chắc muốn xóa size <?php echo $row['sizename']; ?> không?')" href="Size_delete.php?sizeid=<?php echo $row['sizeid']; ?>">Xóa</a>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Add jQuery to the page -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            $('#sizeTableBody tr').each(function() {
                var sizeName = $(this).find('td').eq(1).text().toLowerCase(); // Tên size nằm ở cột thứ 2 (index 1)
                if (sizeName.indexOf(searchValue) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>
</body>
</html>

<?php
// Close the database connection and clear any session error messages
$connect->close();
unset($_SESSION["size_error"]);
?>
