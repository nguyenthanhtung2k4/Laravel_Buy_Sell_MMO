<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Quản Lý Bài Viết | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách bài viết'
];
$body['header'] = '';
$body['footer'] = '';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-blog"></i> Blogs</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH BÀI VIẾT
                        </div>
                        <div class="d-flex">
                            <a type="button" href="<?=BASE_URL('admin/add-new')?>"
                                class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i
                                    class="ri-add-line fw-semibold align-middle"></i> Thêm bài viết</a>
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
                                        <th scope="col">Tiêu đề bài viết</th>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php $i=0; foreach ($TN->get_list(" SELECT * FROM `news` WHERE `id` IS NOT NULL ORDER BY id DESC ") as $row) {?>
                                      
                                                                        <tr>
                                        <td class="text-center">
                                            <div class="form-check form-check-md d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input checkbox_users" data-id="<?=$row['id']?>" name="checkbox_users" value="<?=$row['id']?>">
                                            </div>
                                        </td>
                                        <td><a class="text-primary" href="<?=BASE_URL('client/blog/')?><?=$row['code']?>"><?=$row['tieude']?> [ID: <?=$row['id']?>]</a>
                                        </td>
                                        <td>
                                         <img src="<?=$row['images'];?>" width="100px"></td>
                                        <td class="text-center fs-base">
                                            <a href="<?=BASE_URL('admin/edit-new/');?><?=$row['id'];?>" class="btn btn-sm btn-primary shadow-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit">
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
        title: "Xác Nhận Xóa Bài Viết",
        message: "Bạn có chắc chắn muốn xóa bài viết ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeNew.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(respone) {
                    if (respone.status == 'success') {
                     showMessage(respone.msg, "success");
                     setTimeout(function() {
                     location.reload(); }, 2000);
                  } else {
                     showMessage(respone.msg, respone.status);
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
$(function() {
    $('#check_all_checkbox_users').on('click', function() {
        $('.checkbox_users').prop('checked', this.checked);
    });
    $('.checkbox_users').on('click', function() {
        $('#check_all_checkbox_users').prop('checked', $('.checkbox_users:checked')
            .length === $('.checkbox_users').length);
    });
});
</script>


<?php
require_once(__DIR__.'/Footer.php');
?>