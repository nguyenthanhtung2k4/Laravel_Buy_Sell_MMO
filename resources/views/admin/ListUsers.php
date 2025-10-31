<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Quản Lý Thành Viên | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách Tool'
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-users"></i> Users</h1>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-primary">
                                    <svg class="svg-white" xmlns="http://www.w3.org/2000/svg"
                                        enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px"
                                        fill="#000000">
                                        <rect fill="none" height="24" width="24"></rect>
                                        <g>
                                            <path
                                                d="M4,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C2,12.1,2.9,13,4,13z M5.13,14.1C4.76,14.04,4.39,14,4,14 c-0.99,0-1.93,0.21-2.78,0.58C0.48,14.9,0,15.62,0,16.43V18l4.5,0v-1.61C4.5,15.56,4.73,14.78,5.13,14.1z M20,13c1.1,0,2-0.9,2-2 c0-1.1-0.9-2-2-2s-2,0.9-2,2C18,12.1,18.9,13,20,13z M24,16.43c0-0.81-0.48-1.53-1.22-1.85C21.93,14.21,20.99,14,20,14 c-0.39,0-0.76,0.04-1.13,0.1c0.4,0.68,0.63,1.46,0.63,2.29V18l4.5,0V16.43z M16.24,13.65c-1.17-0.52-2.61-0.9-4.24-0.9 c-1.63,0-3.07,0.39-4.24,0.9C6.68,14.13,6,15.21,6,16.39V18h12v-1.61C18,15.21,17.32,14.13,16.24,13.65z M8.07,16 c0.09-0.23,0.13-0.39,0.91-0.69c0.97-0.38,1.99-0.56,3.02-0.56s2.05,0.18,3.02,0.56c0.77,0.3,0.81,0.46,0.91,0.69H8.07z M12,8 c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S11.45,8,12,8 M12,6c-1.66,0-3,1.34-3,3c0,1.66,1.34,3,3,3s3-1.34,3-3 C15,7.34,13.66,6,12,6L12,6z">
                                            </path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?=format_cash($TN->num_rows("SELECT * FROM `users`"))?>                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">TỔNG THÀNH VIÊN</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-secondary">
                                    <i class="fa-solid fa-money-bill fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?=format_cash($TN->get_row("SELECT SUM(`money`) FROM `users` ")['SUM(`money`)']);?>đ                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">SỐ DƯ CÒN LẠI</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-warning">
                                    <i class="fa-solid fa-user-tie fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?=format_cash($TN->num_rows("SELECT * FROM `users` WHERE `level`='1'"))?>                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">ADMIN</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-danger">
                                    <i class="fa-solid fa-lock fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?=format_cash($TN->num_rows("SELECT * FROM `users` WHERE `banned`='1'"))?>                                   </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Banned</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH THÀNH VIÊN
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <input type="hidden" value="<?=$getUser['token']?>" id="token">
                        </form>
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <div class="form-check form-check-md d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input" name="check_all" id="check_all_checkbox_users" value="option1">
                                            </div>
                                        </th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Số dư khả dụng</th>
                                        <th scope="col" class="text-center">Tổng nạp</th>
                                        <th scope="col" class="text-center">Chiết khấu</th>
                                        <th scope="col" class="text-center">Cấp bậc</th>
                                        <th scope="col" class="text-center">Trạng thái</th>
                                        <th scope="col" class="text-center">Hoạt động</th>
                                        <th scope="col" class="text-center">Thời gian tham gia</th>
                                        <th scope="col">Hoạt động gần đây</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php $i=0; foreach ($TN->get_list("SELECT * FROM `users` ORDER BY `id` DESC") as $row) {?>
                                        
                                                                        <tr>
                                        <td class="text-center">
                                            <div class="form-check form-check-md d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input checkbox_users" data-id="<?=$row['id']?>" name="checkbox_users" value="<?=$row['id']?>">
                                            </div>
                                        </td>
                                        <td><a class="text-primary" href="<?=BASE_URL('admin/user-edit/')?><?=$row['id']?>"><?=$row['username']?>                                                [ID <?=$row['id']?>]</a>
                                        </td>
                                        <td>
                                            <i class="fa fa-envelope" aria-hidden="true"></i> <?=$row['email']?>                                        </td>
                                        <td class="text-right">
                                            <b style="color:blue;"><?=format_cash($row['money'])?>đ</b>
                                        </td>
                                        <td class="text-right">
                                            <b style="color:red;"><?=format_cash($row['total_money'])?>đ</b>
                                        </td>
                                        <td class="text-right">
                                            <b><?=$row['discount']?>%</b>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['level'] == 1){
                                                echo "<span class='badge bg-danger'>Admin</span>";
                                            } else {
                                               echo "<span class='badge bg-success'>Thành Viên</span>";  
                                            }
                                            ?>
                                            
                                            </td>
                                        <td class="text-center">
                                           <?=display_banned($row['banned'])?>                                     </td>
                                        <td class="text-center"><?=display_online($row['time_session'])?></td>
                                        <td class="text-center"><?=format_date($row['create_date'])?></td>
                                        <td><span><?=timeAgo($row['time_session'])?></span></td>
                                        <td class="text-center fs-base">
                                            <a href="<?=BASE_URL('admin/user-edit/')?><?=$row['id']?>" class="btn btn-sm btn-primary shadow-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                <i class="fa fa-fw fa-edit"></i> Edit
                                            </a>
                                            <a type="button" onclick="RemoveRow(<?=$row['id']?>)" class="btn btn-sm btn-danger shadow-danger btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                                                                    </td>
                                    </tr>
                                                 <?php }?>                     
                                                                    </tbody>
                                <tfoot>
                                    
                                    </td>
                                </tr></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<script>
function RemoveRow(id) {
    cuteAlert({
        type: "question",
        title: "Xác Nhận Xóa Thành Viên",
        message: "Bạn có chắc chắn muốn xóa thành viên ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeUser.php",
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
<?php
require_once(__DIR__.'/Footer.php');
?>