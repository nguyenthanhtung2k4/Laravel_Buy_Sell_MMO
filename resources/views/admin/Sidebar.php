<body>


    

    <div id="loader">
        <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/loader.svg" alt="">
    </div>

    <div class="page">
        <header class="app-header">

            <div class="main-header-container container-fluid">

                <div class="header-content-left">

                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="<?=BASE_URL('admin');?>" class="header-logo">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="desktop-logo">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-logo">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="desktop-dark">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-dark">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo"
                                    class="desktop-white">
                                <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-white">
                            </a>
                        </div>
                    </div>
                    
                    <div class="header-element">
                        <a aria-label="Hide Sidebar"
                            class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                            data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                    </div>
                </div>

                <div class="header-content-right">
                    <div class="header-element header-search">
                        <a href="/" class="header-link">
                            <i class="bx bx-user-circle header-link-icon"></i>
                        </a>
                    </div>

                </div>

            </div>

        </header>
        
        <aside class="app-sidebar sticky" id="sidebar">

            <div class="main-sidebar-header">
                <a href="<?=BASE_URL('admin');?>" class="header-logo">
                    <img src="<?=$TN->site('logo');?>" alt="logo" class="desktop-logo">
                    <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-logo">
                    <img src="<?=$TN->site('logo');?>" alt="logo" class="desktop-dark">
                    <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-dark">
                    <img src="<?=$TN->site('logo');?>" alt="logo" class="desktop-white">
                    <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="toggle-white">
                </a>
            </div>

            <div class="main-sidebar" id="sidebar-scroll">

                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                        </svg>
                    </div>
                         <ul class="main-menu">
                        <li class="slide__category"><span class="category-name">Main</span></li>
                        <li class="slide">
                            <a href="<?= BASE_URL('admin'); ?>" class="side-menu__item ">
                                <i class="bx bxs-dashboard side-menu__icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bx-history side-menu__icon'></i>
                                <span class="side-menu__label">Lịch sử</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide"> 
                                    <a href="<?= BASE_URL('admin/his-code'); ?>" class="side-menu__item ">Lịch sử mua code</a>
                                </li>
                                <li class="slide"> 
                                    <a href="<?= BASE_URL('admin/his-hosting'); ?>" class="side-menu__item ">Lịch sử mua hosting</a>
                                </li>
                                <li class="slide"> 
                                    <a href="<?= BASE_URL('admin/his-vps'); ?>" class="side-menu__item ">Lịch sử mua vps</a>
                                </li>
                                <li class="slide"> 
                                    <a href="<?= BASE_URL('admin/his-domain'); ?>" class="side-menu__item ">Lịch sử mua tên miền</a>
                                </li>
                                <li class="slide"> 
                                    <a href="<?= BASE_URL('admin/his-cron'); ?>" class="side-menu__item ">Lịch sử thuê cron</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Dịch vụ</span></li>
                        
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bx-cart side-menu__icon'></i>
                                <span class="side-menu__label">Mã Nguồn</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                            <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-seller'); ?>" class="side-menu__item ">Đơn duyệt người bán hàng</a>
                                </li>
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-server-code'); ?>" class="side-menu__item ">Chuyên mục</a>
                                </li>
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-code'); ?>" class="side-menu__item ">Danh sách mã nguồn</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bx-cart side-menu__icon'></i>
                                <span class="side-menu__label">Hosting</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-server-hosting'); ?>" class="side-menu__item ">Server hosting</a>
                                </li>
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-hosting'); ?>" class="side-menu__item ">Danh sách hosting</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);"
                                class="side-menu__item ">
                                <i class='bx bx-cart side-menu__icon'></i>
                                <span class="side-menu__label">VPS</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-vps'); ?>" class="side-menu__item ">Danh sách vps</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);"
                                class="side-menu__item ">
                                <i class='bx bx-cart side-menu__icon'></i>
                                <span class="side-menu__label">Tên Miền</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-domain'); ?>" class="side-menu__item ">Danh sách tên miền</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);"
                                class="side-menu__item ">
                                <i class='bx bx-cart side-menu__icon'></i>
                                <span class="side-menu__label">Tên Miền</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-server-cron'); ?>" class="side-menu__item ">Server cronjob</a>
                                </li>
                                <li class="slide">
                                    <a href="<?= BASE_URL('admin/list-cron'); ?>" class="side-menu__item ">Danh sách cronjob</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Quản lý</span></li>
                        
                        <li class="slide">
                            <a href="<?=BASE_URL('admin/Key');?>"
                                class="side-menu__item ">
                                <i class="bx bxs-user side-menu__icon"></i>
                                <span class="side-menu__label">KEY</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="<?=BASE_URL('admin/ListUsers');?>"
                                class="side-menu__item ">
                                <i class="bx bxs-user side-menu__icon"></i>
                                <span class="side-menu__label">Thành viên</span>
                            </a>
                        </li>
                        <!-- <li
                            class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bxs-wallet-alt side-menu__icon'></i>
                                <span class="side-menu__label">KEY</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/OverView');?>" class="side-menu__item ">Tổng quan</a>
                                </li>
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/Hmacs');?>" class="side-menu__item ">Chứ ký số</a>
                                </li>
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/Licenses');?>" class="side-menu__item ">Tokens</a>
                                </li>
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/Logs');?>" class="side-menu__item ">Logs</a>
                                </li>
                            </ul>
                        </li> -->
                        <li
                            class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bxs-wallet-alt side-menu__icon'></i>
                                <span class="side-menu__label">Nạp tiền</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/ListBank');?>" class="side-menu__item ">Ngân hàng</a>
                                </li>
                            </ul>
                        </li>
                        <li class="slide has-sub ">
                            <a href="javascript:void(0);" class="side-menu__item ">
                                <i class='bx bxl-blogger side-menu__icon'></i>
                                <span class="side-menu__label">Bài viết</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/add-new');?>" class="side-menu__item ">Viết bài mới</a>
                                </li>
                                <li class="slide">
                                    <a href="<?=BASE_URL('admin/list-new');?>" class="side-menu__item ">Tất cả bài viết</a>
                                </li>
                            </ul>
                        </li>

                        <li class="slide__category"><span class="category-name">Cài đặt hệ thống</span></li>
                      
                        <li class="slide mb-5">
                            <a href="/admin/Setting"
                                class="side-menu__item ">
                                <i class="bx bx-cog side-menu__icon"></i>
                                <span class="side-menu__label">Cài đặt</span>
                            </a>
                        </li>
                    </ul>
                    <div class="slide-right" id="slide-right">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                            width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                        </svg>
                    </div>
                </nav>

            </div>

        </aside>
 <script>

    var currentUrl = window.location.pathname;

    var menuItems = document.querySelectorAll('.side-menu__item');
    menuItems.forEach(function(item) {
        if (item.getAttribute('href') === currentUrl) {
            item.classList.add('active');
        }
    });
</script>