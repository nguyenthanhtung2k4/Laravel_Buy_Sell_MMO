<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Chỉnh Sửa Bài Viết | ' . $TN->site('title');
$body = [
    'title' => 'Chỉnh sửa bài viết'
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
if(isset($_GET['id']) && $getUser['level'] == 1)
{
    $row = $TN->get_row(" SELECT * FROM `news` WHERE `id` = '".xss($_GET['id'])."'  ");
    if(!$row)
    {
        die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
    }
}
else
{
    die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
}
if(isset($_POST['Save']) && $getUser['level'] == 1)
{
    $isUpdate= $TN->update("news", array(
        'tieude'       => xss($_POST['tieude']),
        'code'       => create_slug(xss($_POST['tieude'])),
        'images'       => xss($_POST['images']),
        'noidung'       => xss($_POST['noidung']),
        'status'       => xss($_POST['status']),
        'create_date'       => time(),
    ), " `id` = '".$row['id']."' ");
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
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?= BASE_URL('admin/list-new'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Chỉnh Sửa Bài Viết
            </h1>
        </div>
        <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Chỉnh Sửa Bài Viết 
                            </div>
                        </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                    
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Tên Bài Viết: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên Bài Viết" name="tieude" value="<?=$row['tieude']?>">
                                    </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Hình Ảnh: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập url ảnh" name="images" value="<?=$row['images']?>">
                                    </div>
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Mô tả chi tiết:</label>
                                    <textarea class="noidung" id="noidung"
                                        name="noidung"><?=$row['noidung']?></textarea>
                                </div>
                            <div class="col-sm-4 mb-2"
                                    style="">
                                    <label class="form-label"
                                        for="example-hf-email">Trạng Thái</label>
                                <select class="form-control" name="status" required>
                                    <option <?= $row['status'] == '1' ? 'selected' : ''; ?> value="1">Hoạt Động</option>
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
    require_once(__DIR__."/Footer.php");
?>
<script>
CKEDITOR.replace("noidung");
</script>