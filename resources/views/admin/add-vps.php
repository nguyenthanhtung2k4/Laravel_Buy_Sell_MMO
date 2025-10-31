<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm VPS | ' . $TN->site('title');
$body = [
    'title' => 'Thêm VPS'
];
$body['header'] = '';
$body['footer'] = '';

require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>

<?php
if (isset($_POST['Save']) && $getUser['level'] == 1) {
    $isInsert = $TN->insert("tbl_list_vps", [
        'namevps'   => xss($_POST['namevps']),
        'cpu'       => xss($_POST['cpu']),
        'ram'       => xss($_POST['ram']),
        'disk'      => xss($_POST['disk']),
        'ip'        => xss($_POST['ip']),
        'price'     => xss($_POST['price']),
        'bandwidth' => xss($_POST['bandwidth']),
        'status'    => xss($_POST['status']),
        'daban'     => 0,
        'view'      => 0
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm VPS thành công!")){location.href = "' . BASE_URL('admin/list-vps') . '";}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-sm me-2" href="<?= BASE_URL('admin/list-vps'); ?>">
                    <i class="fa fa-arrow-left"></i>
                </a>
                Thêm VPS Mới
            </h1>
        </div>

        <form action="" method="POST">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Tên VPS</label>
                                    <input type="text" class="form-control" name="namevps" placeholder="VD: VPS 1" required>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label class="form-label">CPU</label>
                                    <input type="text" class="form-control" name="cpu" placeholder="VD: 1" required>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label class="form-label">RAM</label>
                                    <input type="text" class="form-control" name="ram" placeholder="VD: 1" required>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label class="form-label">Ổ cứng (Disk)</label>
                                    <input type="text" class="form-control" name="disk" placeholder="VD: 20" required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Địa chỉ IP</label>
                                    <input type="text" class="form-control" name="ip" placeholder="VD: 1 IP" required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Băng thông (Bandwidth)</label>
                                    <input type="text" class="form-control" name="bandwidth" placeholder="VD: 100 Mb/10 Mb" required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Giá</label>
                                    <input type="text" class="form-control" name="price" placeholder="VD: 70000" required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-control" name="status" required>
                                        <option value="1">Hiển thị</option>
                                        <option value="0">Ẩn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" name="Save" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Thêm VPS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once(__DIR__."/Footer.php"); ?>
