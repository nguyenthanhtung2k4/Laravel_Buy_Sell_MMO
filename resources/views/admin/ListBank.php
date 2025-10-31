<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm Ngân Hàng | ' . $TN->site('title');
$body['header'] = '';
$body['footer'] = '';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>
<?php
if (isset($_POST['ThemNganHang']) && $getUser['level'] == 1) {
    $isInsert = $TN->insert("bank", [
        'short_name'    => check_string($_POST['short_name']),
        'accountNumber' => check_string($_POST['accountNumber']),
        'accountName'   => check_string($_POST['accountName']),
        'token'    => check_string($_POST['token'])
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
    }
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-tags"></i> Ngân hàng</h1>
            <div class="ms-md-1 ms-0">
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH NGÂN HÀNG 
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2"
                            class="btn btn-sm btn-primary shadow-primary"><i
                                class="ri-add-line fw-semibold align-middle"></i> Thêm Ngân hàng mới</button>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                             <input type="hidden" value="<?=$getUser['token']?>" id="token">
                        </form>
                        <div class="table-responsive mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center">Logo</th>
                                        <th class="text-center">Ngân Hàng</th>
                                        <th class="text-center">STK</th>
                                        <th class="text-center">CTK</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php foreach ($TN->get_list("SELECT * FROM `bank` ORDER BY `id` DESC") as $row) {
                                        
                                         $bank_name = $row['short_name'];
                                       foreach ($TN->get_list("SELECT * FROM `api_logo` WHERE `shortName` = '$bank_name'") as $bank)
                                      
                                     ?>
                                                                        <tr>
                                        <td><b>[<?=$row['id'];?>]</b>
                                        </td>
                                         <td class="text-center"><img src="<?=$bank['logo'];?>" height="50">
                                        </td>
                                        <td class="text-center"><span style="font-size: 15px;"
                                                class="badge bg-danger-gradient"><?=$row['short_name']?></span>
                                        </td>
                                         <td class="text-center"><?=$row['accountNumber']?>
                                        </td>
                                         <td class="text-center"><?=$row['accountName']?>
                                        </td>
                                        <td class="text-center">
                                            <a type="button" onclick="RemoveRow(<?=$row['id'];?>)"
                                                class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                                     <?php }?>                     
                                                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModalScrollable2" tabindex="-1" aria-labelledby="exampleModalScrollable2"
    data-bs-keyboard="false" aria-hidden="true">
    <!-- Scrollable modal -->
    <div class="modal-dialog modal-dialog-centered modal-lg dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa-solid fa-plus"></i> Tạo List Bank
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Ngân Hàng (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                               <select class="form-control" name="short_name">
                                                    <option value="">--- chọn ngân hàng -- </option>
                                                    <?php foreach ($TN->get_list("SELECT * FROM `api_logo`") as $bank) {?>
                                            <option value="<?=$bank['shortName'];?>"><?=$bank['name'];?></option>
                                        <?php } ?>
                                                </select>
                                <span class="input-group-text">
                                    <i class="fa-solid fa-code"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                         <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Chủ Tài Khoản (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="accountName" id="accountName" required>
                                <span class="input-group-text">
                                    <i class="fa-solid fa-code"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                         <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Số Tài Khoản (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="accountNumber" id="accountNumber" required>
                                <span class="input-group-text">
                                    <i class="fa-solid fa-code"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Token (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="token" id="token" required>
                                <span class="input-group-text">
                                    <i class="fa-solid fa-code"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light " data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="ThemNganHang" class="btn btn-primary shadow-primary btn-wave"><i
                            class="fa fa-fw fa-plus me-1"></i>
                        Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function RemoveRow(id) {
    cuteAlert({
        type: "question",
        title: "Xác Nhận Xóa Bank",
        message: "Bạn có chắc chắn muốn xóa ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeBank.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(respone) {
                    if (respone.status == 'success') {
                        cuteToast({
                            type: "success",
                            message: respone.msg,
                            timer: 5000
                        });
                        location.reload();
                    } else {
                        cuteAlert({
                            type: "error",
                            title: "Error",
                            message: respone.msg,
                            buttonText: "Okay"
                        });
                    }
                },
                error: function() {
                    alert(html(response));
                    location.reload();
                }
            });
        }
    })
}
</script>
<script>
$(function() {
    $('#datatable1').DataTable();
});
</script>
<script>
$(function() {
    $('#datatable2').DataTable();
});
</script>
<?php
require_once(__DIR__.'/Footer.php');
?>