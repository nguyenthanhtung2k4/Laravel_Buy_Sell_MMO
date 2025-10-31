<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Chỉnh Sửa Đơn Bán Hàng | ' . $TN->site('title');
$body = [
    'title' => 'Chỉnh sửa đơn bán hàng'
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
    $row = $TN->get_row(" SELECT * FROM `author_info` WHERE `id` = '".xss($_GET['id'])."'  ");
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
    $isUpdate= $TN->update("users", array(
        'seller'       => xss($_POST['seller']),
    ), " `id` = '".$row['user_id']."' ");
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
                <a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?= BASE_URL('admin/list-seller'); ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Chỉnh Sửa Đơn Bán Hàng 
            </h1>
        </div>
        <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Chỉnh Sửa Đơn Bán Hàng 
                            </div>
                        </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                    
                        <div class="card-body">
                            <div class="col-sm-12 mb-2"
                                    style="">
                                    <label class="form-label"
                                        for="example-hf-email">Trở Thành Người Bán</label>
                                <select class="form-control" name="seller" required>
                                    <option <?= $getUser['seller'] == '1' ? 'selected' : ''; ?> value="1">Có</option>
                                    <option <?= $getUser['seller'] == '0' ? 'selected' : ''; ?> value="0">Không</option>
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