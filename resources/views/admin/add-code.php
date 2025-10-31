<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm Mã Nguồn | ' . $TN->site('title');
$body = [
    'title' => 'Thêm Mã Nguồn'
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
    $isInsert = $TN->insert("tbl_list_code", [
        'user_id'       => check_string($getUser['id']),
        'name'       => xss($_POST['name']),
        'code'       => create_slug(xss($_POST['name'])),
        'price'       => xss($_POST['price']),
        'content'       => xss($_POST['content']),
        'images'       => xss($_POST['images']),
        'list_images'       => xss($_POST['list_images']),
        'intro'       => $_POST['intro'],
        'link_down'       => buiducthanh_enc(xss($_POST['link_down'])),
        'sevice_code'       => xss($_POST['sevice_code']),
        'sale'       => xss($_POST['sale']),
        'ghim'       => xss($_POST['ghim']),
        'status'       => xss($_POST['status']),
        'create_date'       => time(),
        'update_date'       => time(),
        'hmac_id'   => $_POST['hmac_id']
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm thành công !")){location.href = "' . BASE_URL('admin/list-code') . '";}</script>');
    } else { 
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?= BASE_URL('admin/list-code'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Thêm Mã Nguồn
            </h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Thêm Mã Nguồn
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Tên mã nguồn <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên mã nguồn" name="name" required>
                                </div>
                            <div class="col-md-6">
                                    <div class="mb-4">
                                    <label class="form-label" for="example-hf-email">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" id="example-group1-input3"
                                            name="price">
                                    </div>
                                  </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Chiết khấu giảm giá <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-percent"></i>
                                            </span>
                                            <input type="number" class="form-control"
                                                name="sale">
                                        </div>
                                    </div>
                                 </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link banner <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    name="images" placeholder="Nhập link banner" required>
                            </div>
                           <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link ảnh mô tả <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="list_images"
                                                                    placeholder="Nhập link ảnh mô tả (mỗi dùng 1 link)"
                                                                    rows="6"></textarea>
                            </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Mô tả code</label>
                                <textarea type="text" class="form-control" id="intro" name="intro"></textarea>
                            </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link tải code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    name="link_down" placeholder="Nhập link tải code" required>
                            </div>


                                                                      <div class="col-md-3">
                                                <label class="form-label">HMAC</label>
                                                <select name="hmac_id" class="form-select" required>
                                                      <?php foreach ($TN->get_list('SELECT * FROM hmacs ORDER BY id DESC') as $h): ?>
                                                            <option value="<?= $h['id'] ?>">
                                                                  <?= htmlspecialchars($h['name']) ?></option>
                                                      <?php endforeach; ?>
                                                </select>
                                          </div>



                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Nội dung mô tả</label>
                                <input type="text" class="form-control" name="content"
                                    placeholder="Nhập nội dung mô tả" required>
                            <small>Mỗi nội dung cách nhau bằng dấu phẩy</small>
                                </div>
                                        <div class="col-sm-4 mb-2">
                                    <label class="form-label"
                                        for="example-hf-email">Danh mục</label>
                                            <div class="form-line">
                                                <select class="form-control select2bs4" name="sevice_code" required>
                                                    <?php foreach ($TN->get_list("SELECT * FROM `sevice_code` ORDER BY `id` ASC") as $row) { ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                    </div>
                            <div class="col-sm-4 mb-2">
                                    <label class="form-label"
                                        for="example-hf-email">Ghim đầu trang</label>
                                <select class="form-control" name="ghim" required>
                                    <option value="1">Bật</option>
                                    <option value="0">Tắt</option>
                                </select>
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
CKEDITOR.replace("intro");
</script>