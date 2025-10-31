<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm Bài Viết | ' . $TN->site('title');
$body = [
    'title' => 'Thêm Bài Viết'
];
$body['header'] = '
';
$body['footer'] = '
';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>
<?php
if(isset($_POST['Save']) && $getUser['level'] == 1)
{
    $isInsert = $TN->insert("news", [
        'tieude'       => xss($_POST['tieude']),
        'code'         => create_slug(xss($_POST['tieude'])),
        'noidung'      => xss($_POST['noidung']),
        'images'       => xss($_POST['images']),
        'status'       => xss($_POST['status']),
        'create_date'       => time(),
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm thành công !")){location.href = "' . BASE_URL('admin/list-new') . '";}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?= BASE_URL('admin/list-new'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Thêm Bài Viết
            </h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Thêm Bài Viết
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Tên bài viết <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên bài viết" name="tieude" required>
                                </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link banner <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    name="images" placeholder="Nhập link banner" required>
                            </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Nội dung</label>
                                <textarea type="text" class="form-control" id="noidung" name="noidung"></textarea>
                            </div>
                            <div class="col-sm-4 mb-2">
                                    <label class="form-label"
                                        for="example-hf-email">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">Hoạt Động</option>
                                    <option value="0">Bảo Trì</option>
                                </select>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" name="Save" class="btn btn-primary shadow-primary">
                                    <i class="fa fa-fw fa-save me-1"></i> Thêm Ngay 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<?php 
    require_once(__DIR__."/Footer.php");
?>
<script>
CKEDITOR.replace("noidung");
</script>