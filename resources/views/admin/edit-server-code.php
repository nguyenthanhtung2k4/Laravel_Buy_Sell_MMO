<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Chỉnh Sửa Dịch Vụ | ' . $TN->site('title');
$body = [
    'title' => 'Chỉnh sửa dịch vụ'
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
    $row = $TN->get_row(" SELECT * FROM `sevice_code` WHERE `id` = '".xss($_GET['id'])."'  ");
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
    $isUpdate= $TN->update("sevice_code", array(
        'name'       => xss($_POST['name']),
        'status'       => xss($_POST['status']),
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
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?=BASE_URL('admin/list-server-code')?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Chỉnh Sửa Dịch Vụ
            </h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Chỉnh Sửa Dịch Vụ
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 gy-2">
                            <div class="col-sm-12 mb-2">
                                    <label class="form-label" for="example-hf-email">Tên dịch vụ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?=$row['name']?>"
                                    placeholder="Nhập tên mã nguồn" required>
                            </div>
                            <div class="col-sm-4 mb-2">
                                    <label class="form-label"
                                        for="example-hf-email">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option <?= $row['status'] == '1' ? 'selected' : ''; ?> value="1">Hoạt Động</option>
                                    <option <?= $row['status'] == '0' ? 'selected' : ''; ?> value="0">Bảo Trì</option>
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