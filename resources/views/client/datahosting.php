<?php
$title = 'Chi tiết Hosting - ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
?>
<?php
if (isset($_GET['id'])) {
    $id = xss($_GET['id']);
    $row = $TN->get_row("SELECT * FROM `tbl_his_hosting` WHERE `id` = '$id'");

    if (!$row) {
        echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
        exit;
    }
} else {
    echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
    exit;
}
$server = $TN->get_row("SELECT * FROM `server_hosting` WHERE `uname` = '{$row['server']}'");
?>

<div class="w-breadcrumb-area">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="/assets/images/banner-bg-03.png" alt="img">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Chi tiết Hosting</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    Chi tiết Hosting <?=$row['name']?>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Bảng Thông Tin Cpanel -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Thông Tin Cpanel</div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr><th>Trạng thái</th><td><?= status_hosting($row['status']); ?></td></tr>
                        <tr><th>Tên miền</th><td><?= $row['domain'] ?></td></tr>
                        <tr><th>Cpanel login</th><td><a href="<?= $server['hostname'] ?>:2083" target="_blank"><?= $server['hostname'] ?>:2083</a></td></tr>
                        <tr><th>Tài khoản</th><td><span class="masked">******</span> <i class="fa fa-eye toggle-show" data-target="username"></i></td></tr>
                        <tr><th>Mật khẩu</th><td><span class="masked">******</span> <i class="fa fa-eye toggle-show" data-target="password"></i></td></tr>
                        <tr><th>Mail đăng ký</th><td><?= $row['email'] ?></td></tr>
                        <tr><th>Ngày mua</th><td><?= $row['create_date'] ?></td></tr>
                        <tr><th>Ngày hết hạn</th><td><?= $row['end_day'] ?></td></tr>
                        <tr><th>Gia hạn tự động</th><td><input type="checkbox" checked disabled> Gia hạn</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bảng Điều Khiển -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Bảng Điều Khiển</div>
                <div class="card-body d-grid gap-2">
                    <a href="#" class="btn btn-primary">Đặt lại mật khẩu</a>
                    <a href="#" class="btn btn-warning text-dark">Reset</a>
                    <a href="#" class="btn btn-info text-white">Gia hạn</a>
                    <a href="#" class="btn btn-cyan text-white" style="background-color:#45e6d2;">Nâng cấp</a>
                    <a href="#" class="btn btn-dark">Đổi quyền quản trị</a>
                    <a href="#" class="btn btn-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-show').forEach(el => {
        el.addEventListener('click', () => {
            const span = el.previousElementSibling;
            if (span.classList.contains('masked')) {
                span.classList.remove('masked');
                span.textContent = el.dataset.target === "username" ? "<?= $row['taikhoan'] ?>" : "<?= $row['matkhau'] ?>";
            } else {
                span.classList.add('masked');
                span.textContent = "******";
            }
        });
    });
</script>

<style>
    .masked {
        font-weight: bold;
        letter-spacing: 2px;
    }
    .fa-eye {
        cursor: pointer;
        margin-left: 10px;
    }
</style>

<?php require_once __DIR__ . '/footer.php'; ?>
