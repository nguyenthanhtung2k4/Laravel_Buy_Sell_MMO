<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

$title = 'Thêm Gói Hosting | ' . $TN->site('title');
$body = [
    'title' => 'Thêm Gói Hosting'
];
$body['header'] = '';
$body['footer'] = '';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();

if (isset($_POST['Save'])) {
    // Lấy giá trị từ form
    $name = xss($_POST['name']);
    $code = xss($_POST['code']);
    $language = xss($_POST['language']);
    $cpmod = xss($_POST['cpmod']);
    $price = xss($_POST['price']);
    $dungluong = xss($_POST['dungluong']);
    $bangthong = xss($_POST['bangthong']);
    $miencon = xss($_POST['miencon']);
    $mienkhac = xss($_POST['mienkhac']);
    $mienbidanh = xss($_POST['mienbidanh']);
    $content = xss($_POST['content']);
    $status = $_POST['status'];
    $cate_id = $_POST['cate_id'];

    // Thêm gói hosting vào bảng tbl_list_hosting
    $isInsert = $TN->insert("tbl_list_hosting", [
        'cate_id'      => check_string($cate_id),
        'name'         => $name,
        'code'         => create_slug($code),
        'language'     => $language,
        'cpmod'        => $cpmod,
        'price'        => $price,
        'dungluong'    => $dungluong,
        'bangthong'    => $bangthong,
        'miencon'      => $miencon,
        'mienkhac'     => $mienkhac,
        'mienbidanh'   => $mienbidanh,
        'content'      => $content,
        'status'       => $status,
        'create_date'  => time(),
        'update_date'  => time(),
    ]);

    // Kiểm tra kết quả insert
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm gói hosting thành công!")){location.href = "' . BASE_URL('admin/list-hosting') . '";}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?= BASE_URL('admin/list-hosting'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Thêm Gói Hosting
            </h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Thêm Gói Hosting
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="name">Tên Gói Hosting <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="code">Mã Gói Hosting</label>
                                    <select class="form-control" name="code" required>
                                        <?php 
                                        foreach ($TN->get_list("SELECT * FROM `server_hosting` ORDER BY `id` ASC") as $row) { 
                                        ?>
                                            <option value="<?= $row['id']; ?>"><?= $row['uname']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <div class="mb-4">
                                        <label class="form-label" for="price">Giá <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="price" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <div class="mb-4">
                                        <label class="form-label" for="dungluong">Dung Lượng <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="dungluong" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <div class="mb-4">
                                        <label class="form-label" for="bangthong">Băng Thông <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="bangthong" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="miencon">Miền Còn Lại</label>
                                    <input type="text" class="form-control" name="miencon">
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="mienkhac">Miền Khác</label>
                                    <input type="text" class="form-control" name="mienkhac">
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="mienbidanh">Miền Bí Danh</label>
                                    <input type="text" class="form-control" name="mienbidanh">
                                </div>

                                <div class="col-sm-3 mb-2">
                                    <label class="form-label" for="language">Ngôn Ngữ</label>
                                    <input type="text" class="form-control" name="language">
                                </div>

                                <div class="col-sm-3 mb-2">
                                    <label class="form-label" for="cpmod">Giao Diện</label>
                                    <input type="text" class="form-control" name="cpmod">
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="status">Trạng Thái</label>
                                    <select class="form-control" name="status" required>
                                        <option value="1">Hoạt Động</option>
                                        <option value="0">Bảo Trì</option>
                                    </select>
                                </div>

                                <div class="col-sm-6 mb-2">
                                    <label class="form-label" for="content">Mô Tả</label>
                                    <textarea class="form-control" name="content" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" name="Save" class="btn btn-primary shadow-primary">
                                    <i class="fa fa-fw fa-save me-1"></i> Thêm Gói Hosting
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
require_once(__DIR__."/Footer.php");
?>
