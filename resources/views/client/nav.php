<body>
    <div class="loader-wrapper">
        <span class="site-loader"> </span>
    </div>
    <script>
        window.addEventListener('load', function() {
            var loadingOverlay = document.querySelector('.loader-wrapper');
            loadingOverlay.style.display = 'none';
        });
    </script>
    <header class="header-primary">
        <div class="container">
            <nav class="navbar navbar-expand-xl justify-content-between">
                <a href="/">
                    <img src="<?=$TN->site('logo');?>" width="150" alt="" />
                </a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="d-block d-xl-none">
                            <div class="logo">
                                <a href="/"><img src="<?=$TN->site('logo');?>" width="150" alt="" /></a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/" role="button" aria-expanded="false">Trang chủ</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Dịch vụ</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/client/list-code" class="dropdown-item"><span>Mã nguồn</span></a>
                                    <!-- <a href="/client/hosting" class="dropdown-item"><span>Hosting</span></a> -->
                                    <!-- <a href="/client/cloudvps" class="dropdown-item"><span>VPS</span></a> -->
                                    <!-- <a href="/client/reg-domain" class="dropdown-item"><span>Tên miền</span></a> -->
                                    <!-- <a href="/client/cronjob" class="dropdown-item"><span>Cronjob</span></a> -->
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Tiện Ích</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/client/whois" class="dropdown-item"><span>Kiểm tra tên miền</span></a>
                                    <a href="/client/ioncube" class="dropdown-item"><span>Mã hoá ionCube</span></a>
                                    <a href="/client/upanh" class="dropdown-item"><span>Lấy URL ảnh</span></a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Nạp tiền</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/client/bank" class="dropdown-item"><span>Ngân hàng</span></a>
                                </li>
                                <li>
                                    <a href="/client/card" class="dropdown-item"><span>Thẻ cào</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Lịch sử</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/client/user-history-code" class="dropdown-item"><span>Lịch sử mua mã nguồn</span></a>
                                </li>
                                <li>
                                    <!-- <a href="/client/user-history-hosting" class="dropdown-item"><span>Lịch sử mua hosting</span></a> -->
                                </li>
                                <li>
                                    <!-- <a href="/client/user-history-vps" class="dropdown-item"><span>Lịch sử mua vps</span></a> -->
                                </li>
                                <li>
                                    <!-- <a href="/client/user-history-domain" class="dropdown-item"><span>Lịch sử mua tên miền</span></a> -->
                                </li>
                                <li>
                                    <!-- <a href="/client/user-history-cron" class="dropdown-item"><span>Lịch sử mua thuê cron</span></a> -->
                                </li>
                            </ul>
                            </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/client/blogs">Tin tức</a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/client/contact">Liên hệ</a>
                        </li>
                    </ul>

                </div>
                <div class="navbar-right d-flex align-items-center gap-2">
                    <a href="/client/user-favorites" class="header-widget" title="Sản phẩm yêu thích">
                        <i class="fas fa-heart"></i>
                        <sup id="numFavorites">
                        <?php if (isset($getUser['id'])): ?>
                        <?= format_cash($TN->num_rows("SELECT * FROM `favorite` WHERE `user_id` = '" . $getUser['id'] . "'")) ?>
                        <?php else: ?>
                        <?= format_cash(0) ?>
                        <?php endif; ?>
                        </sup>
                    </a>
                    <div class="align-items-center">
                    <?php if(empty($getUser['username'])) { ?>
                        <a href="/client/login" class="btn-secondary me-1">
                            Đăng Nhập
                        </a>
                    <?php } else { ?>
                    <div class="dropdown">
                        <button type="button" class="d-flex header-widget" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/<?= !empty($getUser['profile_picture']) ? $getUser['profile_picture'] : 'assets/images/avt.png'; ?>" class="rounded-circle w-40 me-1" alt="">
                        <span>
                        <p class="text-uppercase">
                        <?php
                        if ($getUser['type'] === 'Google') {
                            echo $getUser['name'];
                        } else {
                            echo !empty($getUser['name']) ? $getUser['name'] : $getUser['username'];
                        }
                        ?>
                        </p>
                        <p style="color:red;"><?=format_cash($getUser['money']);?>đ</p>
                        </span>
                        </button>
                            <ul class="dashboard-profile dropdown-menu" style="position: absolute;inset: 0px 0px auto auto;margin: 0px;transform: translate3d(0px, 58.4px, 0px);">
                                <?php if(isset($getUser['username']) && $getUser['level'] == '1') { ?>   
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="/admin"><i class="fa-solid fa-user-tie me-1 fs-10"></i>Admin Dashboard</a>
                                </li>
                                <?php }?>
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="/client/user-dashboard"><i class="fa fa-home me-1 fs-10"></i>Dashboard</a>
                                </li>
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="/client/api-key"><i class="fa-solid fa-code me-1 fs-10"></i>Api Key</a>
                                </li>
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="/client/user-security"><i class="fa-solid fa-shield-halved me-1 fs-10"></i>Bảo Mật</a>
                                </li>
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="/client/user-profile"><i class="fa fa-user me-1 fs-10"></i>Tài khoản</a>
                                </li>
                                <li>
                                    <a class="dashboard-profile-item dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="fa-solid fa-right-from-bracket me-1 fs-10"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                            </div>
                            <?php }?>
                    </div>
                    <button class="navbar-toggler d-block d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>
    <?php
        if(isset($getUser['username']))
        {
            if($getUser['banned'] == 1)
            {
                session_destroy();
                msg_warning("Tài khoản của bạn đã bị khóa.", "", 5000);
            }
            if($getUser['level'] != '1')
            {
            }
        }
        else
        {
        }