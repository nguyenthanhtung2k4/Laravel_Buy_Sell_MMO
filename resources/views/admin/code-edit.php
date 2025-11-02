<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Chỉnh Sửa Mã Nguồn | ' . $TN->site('title');
$body = [
    'title' => 'Chỉnh sửa mã nguồn'
];
$body['header'] = '
';
$body['footer'] = '
';
require_once(__DIR__ . '/Header.php');
require_once(__DIR__ . '/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>
<?php
if (isset($_GET['id']) && $getUser['level'] == 1) {
    $row = $TN->get_row(" SELECT * FROM `tbl_list_code` WHERE `id` = '" . xss($_GET['id']) . "'  ");
    if (!$row) {
        die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
    }
} else {
    die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
}
if (isset($_POST['Save']) && $getUser['level'] == 1) {
    $isUpdate = $TN->update("tbl_list_code", array(
        'user_id' => check_string($getUser['id']),
        'name' => xss($_POST['name']),
        'code' => create_slug(xss($_POST['name'])),
        'price' => xss($_POST['price']),
        'content' => xss($_POST['content']),
        'images' => xss($_POST['images']),
        'list_images' => xss($_POST['list_images']),
        'intro' => $_POST['intro'],
        'sale' => xss($_POST['sale']),
        'link_down' => buiducthanh_enc(xss($_POST['link_down'])),
        'sevice_code' => xss($_POST['sevice_code']),
        'ghim' => xss($_POST['ghim']),
        'status' => xss($_POST['status']),
        'update_date' => time(),
        'hmac_id' => $_POST['hmac_id']
    ), " `id` = '" . $row['id'] . "' ");
    if ($isUpdate) {
        die('<script type="text/javascript">if(!alert("Lưu thành công!")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1"
                    href="<?= BASE_URL('admin/list-code'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                Chỉnh Sửa Mã Nguồn
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Chỉnh Sửa Mã Nguồn
                        </div>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">

                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Tên Mã Nguồn: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên mã nguồn" name="name"
                                        value="<?= $row['name'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="example-hf-email">Giá Bán: <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="price"
                                            value="<?= $row['price'] ?>" required>
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
                                            <input type="number" class="form-control" value="<?= $row['sale'] ?>"
                                                name="sale">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link Banner: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="images" value="<?= $row['images'] ?>"
                                        placeholder="Nhập link banner" required>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link Ảnh Mô Tả: <span
                                            class="text-danger">*</span></label></label>
                                    <textarea class="form-control" name="list_images"
                                        placeholder="Nhập link ảnh mô tả (mỗi dùng 1 link)"
                                        rows="6"><?= $row['list_images'] ?></textarea>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Mô tả chi tiết:</label>
                                    <textarea class="intro" id="intro" name="intro"><?= $row['intro'] ?></textarea>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Link Tải Code: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="link_down"
                                        value="<?= buiducthanh_dec($row['link_down']) ?>" placeholder="Nhập link tải code"
                                        required>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Nội Dung Mô Tả: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="content" value="<?= $row['content'] ?>"
                                        placeholder="Nhập nội dung mô tả" required>
                                    <small>Mỗi Nội Dung Cách Nhau Bằng Dấu Phẩy</small>
                                </div>
<!-- Edit Hmac-id -->
                                <div class="col-md-3">
                                    <label class="form-label">HMAC</label>
                                    <select name="hmac_id" class="form-select" required>
                                        <?php
                                        $current_hmac_id = isset($row['hmac_id']) ? $row['hmac_id'] : 1;
                                        foreach ($TN->get_list('SELECT * FROM hmacs ORDER BY id DESC') as $h):
                                            $selected = ($h['id'] == $current_hmac_id) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $h['id'] ?>" <?= $selected ?>>
                                                <?= htmlspecialchars($h['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-sm-4 mb-2" style="">
                                    <label class="form-label" for="example-hf-email">Danh Mục</label>
                                    <select class="form-control" name="sevice_code" required>
                                        <?php foreach ($TN->get_list("SELECT * FROM `sevice_code` WHERE `id`='" . $row['sevice_code'] . "'") as $category) { ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?>
                                            </option>
                                        <?php } ?>
                                        <?php foreach ($TN->get_list("SELECT * FROM `sevice_code` ORDER BY `id` ASC") as $brand) { ?>
                                            <option value="<?= $brand['id']; ?>"><?= $brand['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2" style="">
                                    <label class="form-label" for="example-hf-email">Ghim Đầu Trang</label>
                                    <select class="form-control" name="ghim" required>
                                        <option <?= $row['ghim'] == '1' ? 'selected' : ''; ?> value="1">Bật</option>
                                        <option <?= $row['ghim'] == '0' ? 'selected' : ''; ?> value="0">Tắt</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2" style="">
                                    <label class="form-label" for="example-hf-email">Trạng Thái</label>
                                    <select class="form-control" name="status" required>
                                        <option <?= $row['status'] == '1' ? 'selected' : ''; ?> value="1">Hoạt Động
                                        </option>
                                        <option <?= $row['status'] == '0' ? 'selected' : ''; ?> value="0">Bảo Trì</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <button name="Save" type="submit" class="btn btn-primary shadow-primary">
                                    <i class="fa fa-fw fa-save me-1"></i> Save
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
require_once(__DIR__ . "/Footer.php");
?>
<script>
    CKEDITOR.replace("intro");
</script>