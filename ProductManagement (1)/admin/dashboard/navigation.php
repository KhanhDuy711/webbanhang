<nav class="main-menu" >
    <div class="" style="margin-bottom:30px;display:flex">
        <div class="icon-user">
            <i class="fa-solid fa-user fa fa-2x" style="color: #c2c4c7;"></i>
        </div>

        <div class="profile" style="color:white">
            <span>Xin Chào,</span>
            <h5>
                <?php if (isset($_SESSION['admin_name'])) {
                    if ($_SESSION['admin_name'])
                        echo $_SESSION['admin_name'];
                } ?>
            </h5>
        </div>
    </div>
    <ul>
        <li>
            <a href="index.php?manage=product">
                <i class="fa-solid fa-shirt fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý sản phẩm
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="index.php?manage=thongke">
            <i class="fa-solid fa-square-poll-vertical fa fa-2x"></i>
                <span class="nav-text">
                    Thống kê
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="index.php?manage=categories">
                <i class="fa fa-2x fa-solid fa-layer-group"></i>
                <span class="nav-text">
                    Quản lý danh mục
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="index.php?manage=color">
                <i class="fa-solid fa-palette fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý màu sắc
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="index.php?manage=size">
                <i class="fa-solid fa-minimize fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý size
                </span>
            </a>

        </li>
        <li>
            <a href="index.php?manage=customer">
                <i class="fa-solid fa-users fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý khách hàng
                </span>
            </a>
        </li>
        <li>
            <a href="index.php?manage=voucher">
                <i class="fa-solid fa-ticket fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý khuyến mãi
                </span>
            </a>
        </li>
        <li>
            <a href="index.php?manage=order">
                <i class="fa-solid fa-file-invoice-dollar fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý đơn đặt hàng
                </span>
            </a>
        </li>
        <li>
            <a href="index.php?manage=sale">
                <i class="fa-solid fa-percent fa fa-2x"></i>
                <span class="nav-text">
                    Quản lý chương trình khuyến mãi
                </span>
            </a>
        </li>
        
    </ul>

    <ul class="logout">
        <li>
            <a onclick="return confirm('Bạn có chắc muốn đăng xuất hay không?')" href="logout.php">
                <i class="fa fa-power-off fa-2x"></i>
                <span class="nav-text">
                    Logout
                </span>
            </a>
        </li>
    </ul>
</nav>