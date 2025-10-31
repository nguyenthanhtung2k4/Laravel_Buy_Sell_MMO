<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Danh Sách Người Bán Hàng | ' . $TN->site('title');
$body = [
    'title' => 'DANH SÁCH NGƯỜI BÁN HÀNG'
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

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-user"></i> Người Bán Hàng</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH NGƯỜI BÁN HÀNG
                        </div>
                        <div class="d-flex">
                        </div>
                    </div>
                    <input type="hidden" value="<?=$getUser['token']?>" id="token">
                    <div class="card-body">
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username Người Bán Hàng</th>
                                        <th>Thông Tin Người Bán Hàng</th>
                                        <th width="20%">Thao Tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach ($TN->get_list(" SELECT * FROM `author_info` WHERE `id` IS NOT NULL ORDER BY id DESC ") as $row) {?>
                                         <tr>
                                        <td><?=$row['id'];?></td>
                                        <td><?=getUser($row['user_id'], 'username');?></td>
                                        <td>
                                            Đội: <b
                                                style="color:red;"><?=$row['team'];?></b><br>
                                            Thành viên của <?=getUser($row['user_id'], 'username');?>: <b style="color:blue;"><?=$row['team_members'];?></b><br>
                                            Tài khoản khác nền tảng này: <b style="color:orange;"><?=$row['other_account'];?></b><br>
                                            Tài khoản khác ở thị trường khác: <b style="color:green;"><?=$row['market_account'];?></b><br>
                                            <?=getUser($row['user_id'], 'username');?> làm việc ở hạng mục: <b style="color:purple;"><?=$row['work_category'];?></b><br>
                                        </td>
                                        <td>
                                            <a type="button"
                                                href="<?=BASE_URL('admin/edit-seller/');?><?=$row['id'];?>"
                                                class="btn btn-sm btn-secondary shadow-secondary btn-wave"
                                                data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a type="button" onclick="RemoveRow(<?=$row['id']?>)"
                                                class="btn btn-sm btn-danger shadow-danger btn-wave"
                                                data-bs-toggle="tooltip" title="Xóa">
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
                </section>
            </div>
        </div>
    </div>
</div>
<script>
function RemoveRow(id) {
    cuteAlert({
        type: "question",
        title: "Xác Nhận Xóa Đơn Này",
        message: "Bạn có chắc chắn muốn xóa đơn ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeSeller.php",
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
<script type="text/javascript">
$.ajax({
    url: "<?=BASE_URL('update.php');?>",
    type: "GET",
    dateType: "text",
    data: {},
    success: function(result) {

    }
});
</script>