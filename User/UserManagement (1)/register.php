<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if(!isset($_SESSION["register_error"])) {
        $_SESSION["register_error"] = "";
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="bg-light">

    <section class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Đăng Ký Tài Khoản</h3>
                        <center> <font color=red><?php echo $_SESSION["register_error"]; ?></font><br></center>
                        <form action="./register_action.php" method="post" id="form-1">

                            <div class="mb-3">
                                <label for="username" class="form-label">Họ và Tên:</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    >
                                <div class="form-text text-danger" id="user-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <input type="text" class="form-control" id="address" name="address"
                                   >
                                <div class="form-text text-danger" id="address-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="VD: 0968381198">
                                <div class="form-text text-danger" id="phone-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="VD: email@domain.com">
                                <div class="form-text text-danger" id="email-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="account" class="form-label">Tài khoản:</label>
                                <input type="text" class="form-control" id="account" name="account"
                                    >
                                <div class="form-text text-danger" id="account-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu:</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Nhập mật khẩu">
                                <div class="form-text text-danger" id="pass-error"></div>
                            </div>

                            <div class="mb-3">
                                <button type="reset" class="btn btn-primary">Reset</button>

                                <button type="submit" class="btn btn-primary">Đăng ký</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    

</body>
<?php
    unset($_SESSION["register_error"]);
?>
</html>
