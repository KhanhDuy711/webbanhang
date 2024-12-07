<?php
session_start();
include "Connect.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caccount = $_POST["caccount"];
    $cpassword = $_POST["cpassword"];

    if (empty($caccount) || empty($cpassword)) {
        $error = "Tài khoản và mật khẩu là bắt buộc.";
    } else {
        $query = "SELECT * FROM customer WHERE caccount = ? AND cpassword = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Lỗi truy vấn: " . $conn->error);
        }

        $stmt->bind_param("ss", $caccount, $cpassword);
        $stmt->execute();

        if ($stmt->error) {
            die("Lỗi khi thực thi truy vấn: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($row['cstatus'] == 0) {
                $lock = "Tài khoản của bạn đã bị khóa!";
            } else {
                $_SESSION["cid"] = $row["cid"];
                $_SESSION["cname"] = $row["cname"];
                header("Location: ../index.php");
                exit();
            }
        } else {
            $error = "Sai tài khoản hoặc mật khẩu.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập khách hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
  <div class="container">
    <div class="card shadow" style="max-width: 400px; margin: auto;">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Đăng nhập</h3>
        <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (isset($lock)) {
                echo '<div class="alert alert-warning">' . $lock . '</div>';
            }
        ?>

        <form method="post" action="">
          <div class="mb-3">
            <label for="caccount" class="form-label">Tài khoản</label>
            <input type="text" class="form-control" id="caccount" name="caccount" placeholder="Nhập tài khoản" required>
          </div>

          <div class="mb-3">
            <label for="cpassword" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Nhập mật khẩu" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
          <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
