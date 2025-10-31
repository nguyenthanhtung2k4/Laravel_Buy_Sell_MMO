<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm Domain | ' . $TN->site('title');
$body = [
    'title' => 'Thêm Domain'
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
if(isset($_POST['Save']) && $getUser['level'] == 1) {
    $isInsert = $TN->insert("tbl_list_domain", [
        'name'   => xss($_POST['name']),
        'price'  => xss($_POST['price']),
        'image'  => xss($_POST['image']),
        'value'  => xss($_POST['value'])
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm domain thành công!")){location.href = "' . BASE_URL('admin/list-domain') . '";}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-sm me-2" href="<?= BASE_URL('admin/list-domain'); ?>">
                    <i class="fa fa-arrow-left"></i>
                </a>
                Thêm Domain Mới
            </h1>
        </div>

        <form action="" method="POST">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Tên domain (ví dụ: COM, NET...)</label>
                                    <input type="text" class="form-control" name="name" required placeholder="VD: COM">
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Giá tiền</label>
                                    <input type="text" class="form-control" name="price" required placeholder="VD: 350000">
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Trạng thái hiển thị</label>
                                    <select class="form-control" name="value" required>
                                        <option value="on">Hiển thị</option>
                                        <option value="off">Ẩn</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Link hình ảnh</label>
                                    <input type="text" class="form-control" name="image" required placeholder="Dán link ảnh logo domain">
                                    <small>Ví dụ: https://imgur.com/abc123.png</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" name="Save" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Thêm Domain
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
