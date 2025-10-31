<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Trang Quản Trị | ' . $TN->site('title');
$body['header'] = '';
$body['footer'] = '';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
include(realpath($_SERVER["DOCUMENT_ROOT"]) . '/config.php');
CheckLogin();
CheckAdmin();
$month = date('m');
$year = date('Y');
?>

     <div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-2"><i class="fa-solid fa-chart-line"></i> Dashboard</h1>
            <div class="float-right">
                
            </div>
        </div>
                <div class="alert alert-secondary alert-dismissible fade show custom-alert-icon shadow-sm" role="alert">
            <h5><?=$config['project'];?> Version: <strong style="color:blue;"><?=$config['version'];?></strong></h5>
            
            <small>Hệ thống sẽ tự động cập nhật phiên bản mới khi bạn truy cập trang này, để tắt chức năng này quý khách
                vào menu <strong>Cài Đặt</strong> -> <strong>Cài đặt chung</strong> -> <strong>Cập nhật phiên bản tự động</strong> -> <strong>Chọn OFF</strong>.</small>
                
            <div class="mt-3">
                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <div class="p-2 bg-light">
                            <i class="fab fa-telegram text-primary me-2"></i>
                            <small>Kênh thông báo cập nhật:</small>
                            <span class="badge bg-warning">Chỉ áp dụng cho website chính hãng</span>                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-light">
                            <i class="fab fa-telegram text-primary me-2"></i>
                            <small>Nhóm tìm kiếm API:</small>
                            <span class="badge bg-warning">Chỉ áp dụng cho website chính hãng</span>                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-light">
                            <i class="fab fa-rocketchat text-success me-2"></i>
                            <small>Nhóm Zalo thông báo:</small>
                            <span class="badge bg-warning">Chỉ áp dụng cho website chính hãng</span>                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-light">
                            <i class="fab fa-rocketchat text-success me-2"></i>
                            <small>Nhóm Zalo trao đổi API:</small>
                            <span class="badge bg-warning">Chỉ áp dụng cho website chính hãng</span>                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2 mt-3">
                <a class="btn btn-primary btn-sm" href="" target="_blank">
                    <i class="fab fa-facebook-messenger me-1"></i> Kiểm tra bản quyền
                </a>
                <a class="btn btn-secondary btn-sm" href="https://t.me/LicensedCode_Bot" target="_blank">
                    <i class="fab fa-telegram me-1"></i> Bot kiểm tra bản quyền
                </a>
                <button class="btn btn-info btn-sm" id="copyLicenseKey" onclick="copyLicenseKey()">
                    <i class="fas fa-copy me-1"></i> Sao chép giấy phép
                </button>
                <button class="btn btn-warning btn-sm ms-auto" id="hideAlert24h">
                    <i class="fas fa-eye-slash me-1"></i> Ẩn trong 24 giờ
                </button>
                <script>
                    function copyLicenseKey() {
                        const licenseKey = 'aeeb422ae3477fbbec7636cb7e20523d';
                        navigator.clipboard.writeText(licenseKey).then(function() {
                            const button = document.getElementById('copyLicenseKey');
                            const originalText = button.innerHTML;
                            button.innerHTML = '<i class="fas fa-check me-1"></i> Đã sao chép';
                            button.classList.remove('btn-info');
                            button.classList.add('btn-success');
                            
                            setTimeout(function() {
                                button.innerHTML = originalText;
                                button.classList.remove('btn-success');
                                button.classList.add('btn-info');
                            }, 2000);
                        }).catch(function(err) {
                            console.error('Không thể sao chép: ', err);
                            alert('Không thể sao chép giấy phép. Vui lòng thử lại.');
                        });
                    }
                </script>
            </div>
        </div>
        <script>
            document.getElementById('hideAlert24h').addEventListener('click', function() {
                localStorage.setItem('hideAlertUntil', Date.now() + 24 * 60 * 60 * 1000);
                this.closest('.alert').style.display = 'none';
            });
            
            document.addEventListener('DOMContentLoaded', function() {
                const hideUntil = localStorage.getItem('hideAlertUntil');
                const alertElement = document.querySelector('.alert-secondary');
                
                if (hideUntil && Date.now() < parseInt(hideUntil) && alertElement) {
                    alertElement.style.display = 'none';
                }
            });
        </script>
        <?php if (empty($TN->site('email_smtp')) || empty($TN->site('pass_email_smtp'))): ?>
        <div class="alert alert-warning alert-dismissible fade show custom-alert-icon shadow-sm" role="alert">
            <svg class="svg-warning" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000">
                <path d="M0 0h24v24H0z" fill="none" />
                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
            </svg>
            Vui lòng cấu hình <b>SMTP</b> để sử dụng toàn bộ tiện ích từ Mail:
            <a class="text-primary" href="https://help.cmsnt.co/huong-dan/huong-dan-cau-hinh-smtp-vao-website-shopclone7/" target="_blank">Xem Hướng Dẫn</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <?php endif; ?>
        
            <div class="row">
            <div class="col-12">
                <div class="text-right mb-3">
                    <img src="https://i.imgur.com/P1wXDsL.png" width="60px">
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card primary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-primary">
                                    <i class="fa-solid fa-users fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Thành viên đăng ký</span>
                                 <h5 class="fw-semibold mb-2"><?=format_cash($TN->num_rows("SELECT * FROM `users`"))?>
                                    <div class="spinner" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-primary-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card danger">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-danger">
                                    <i class="fa-solid fa-money-bill-trend-up fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Tổng tiền đã nạp</span>
                                 <h5 class="fw-semibold mb-2"><?=format_cash($TN->get_row("SELECT SUM(amount) as total FROM invoices")['total'] ?? 0 + $TN->get_row("SELECT SUM(thucnhan) as total FROM cards")['total'] ?? 0)?>
                                    <div class="spinner" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-danger-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-info">
                                    <i class="fa-solid fa-cart-shopping fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                    <span class="fw-semibold text-muted d-block mb-2">Mã nguồn đã bán</span>
                    <h5 class="fw-semibold mb-2">
                       <?=format_cash($TN->num_rows("SELECT * FROM `tbl_his_code`"))?>
                        <div class="spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </h5>
                                <p class="mb-0">
                                    <span class="badge bg-info-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card warning">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-warning">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                    <span class="fw-semibold text-muted d-block mb-2">Doanh thu mã nguồn</span>
                    <h5 class="fw-semibold mb-2">
                        <?= format_cash($TN->get_row("SELECT SUM(price) as total FROM tbl_his_code")['total'] ?? 0) ?>
                        <div class="spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </h5>
                    <p class="mb-0">
                        <span class="badge bg-warning-transparent">Toàn thời gian</span>
                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">GIAO DỊCH GẦN ĐÂY</div>
                        <div class="ms-auto">
                            <img class="text-right" src="https://i.imgur.com/P1wXDsL.png" width="60px">
                        </div>
                    </div>
                </div>
                  <ul class="timeline list-unstyled orders-timeline" style="height:500px;overflow-x:hidden;overflow-y:auto;">
            <li> 
                <?php $i=0; foreach ($TN->get_list("SELECT * FROM `log_balance` WHERE `id` > 0 ORDER BY id DESC LIMIT 50 ") as $row) {?>
                <li> 
                <div class="timeline-time text-end">
                <span class="date"><?=timeAgo($row['time']);?></span>
            </div>
            <div class="timeline-icon">
                <a href="javascript:void(0);"></a>
            </div>
            <div class="timeline-body">
                <div class="d-flex align-items-top timeline-main-content flex-wrap mt-0">
                    <div class="flex-fill">
                        <div class="d-flex align-items-center">
                            <div class="mt-sm-0 mt-2">
                                <p class="mb-0 text-muted"><b style="color: green;"><?=getUser($row['user_id'], 'username');?></b>
                                    <?=$row['content'];?> với giá <b style="color:blue;"><?= format_cash($row['money_change']);?>đ</b>
                                  </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <?php }?>
         </ul> 
      </div> 
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">HOẠT ĐỘNG GẦN ĐÂY</div>
                        <div class="ms-auto">
                            <img class="text-right" src="https://i.imgur.com/P1wXDsL.png" width="60px">
                        </div>
                    </div>
                </div>
                <ul class="timeline list-unstyled orders-timeline" style="height:500px;overflow-x:hidden;overflow-y:auto;">
                <?php $i=0; foreach ($TN->get_list("SELECT * FROM `logs` WHERE `id` > 0 ORDER BY id DESC LIMIT 50 ") as $row) {?>
                <li>
                <div class="timeline-time text-end">
                <span class="date"><?=timeAgo($row['create_date']);?></span>
            </div>
            <div class="timeline-icon">
                <a href="javascript:void(0);"></a>
            </div>
            <div class="timeline-body">
                <div class="d-flex align-items-top timeline-main-content flex-wrap mt-0">
                    <div class="flex-fill">
                        <div class="d-flex align-items-center">
                            <div class="mt-sm-0 mt-2">
                                <p class="mb-0 text-muted"><b style="color: green;"><?=getUser($row['user_id'], 'username');?></b>
                                   Vừa <?=$row['action'];?></b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <?php }?>
         </ul> 
      </div> 
             </div>
    </div>
</div>
<?php
require_once(__DIR__.'/Footer.php');
?>