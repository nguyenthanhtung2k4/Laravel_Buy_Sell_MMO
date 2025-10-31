<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Danh sách Hosting | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách Hosting'
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-cart-shopping"></i> Hosting</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH HOSTING
                        </div>
                        <div class="d-flex">
                            <a type="button" href="<?=BASE_URL('admin/add-hosting')?>"
                               class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i
                                       class="ri-add-line fw-semibold align-middle"></i> Thêm Hosting</a>
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
                                                <input type="checkbox" class="form-check-input" name="check_all" id="check_all_checkbox_hosting" value="option1">
                                            </div>
                                        </th>
                                        <th scope="col">Tên Hosting</th>
                                        <th scope="col">Thông Tin</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; 
                                    foreach ($TN->get_list("SELECT * FROM `tbl_list_hosting` WHERE `status` = 1 ORDER BY id DESC") as $row) { ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check form-check-md d-flex align-items-center">
                                                    <input type="checkbox" class="form-check-input checkbox_hosting" data-id="<?=$row['id']?>" name="checkbox_hosting" value="<?=$row['id']?>">
                                                </div>
                                            </td>
                                            <td>
                                                <a class="text-primary" href="<?=BASE_URL('client/view-hosting/')?><?=$row['code']?>"><?=$row['name']?> [ID: <?=$row['id']?>]</a>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li><b>Language:</b> <?=$row['language']?></li>
                                                    <li><b>CPMod:</b> <?=$row['cpmod']?></li>
                                                    <li><b>Giá:</b> <?=format_cash($row['price'])?>đ</li>
                                                    <li><b>Dung lượng:</b> <?=$row['dungluong']?></li>
                                                    <li><b>Bảng thông số:</b> <?=$row['bangthong']?></li>
                                                    <li><b>Mien con:</b> <?=$row['miencon']?></li>
                                                    <li><b>Mien khac:</b> <?=$row['mienkhac']?></li>
                                                    <li><b>Mien bidanh:</b> <?=$row['mienbidanh']?></li>
                                                    <li><b>Ngày tạo:</b> <?=$row['create_date']?></li>
                                                    <li><b>Ngày cập nhật:</b> <?=$row['update_date']?></li>
                                                </ul>
                                            </td>
                                            <td class="text-center fs-base">
                                                <a href="<?=BASE_URL('admin/hosting-edit/')?><?=$row['id'];?>" class="btn btn-sm btn-primary shadow-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                    <i class="fa fa-fw fa-edit"></i> Edit
                                                </a>
                                                <a type="button" onclick="RemoveRow(<?=$row['id']?>)" class="btn btn-sm btn-danger shadow-danger btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>                     
                                </tbody>
                                <tfoot>
                                </tfoot>
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
        title: "Xác Nhận Xóa Hosting",
        message: "Bạn có chắc chắn muốn xóa Hosting ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeHosting.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(response) {
                    if (response.status == 'success') {
                        showMessage(response.msg, "success");
                        setTimeout(function() {
                            location.reload(); 
                        }, 2000);
                    } else {
                        showMessage(response.msg, response.status);
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
    $('#check_all_checkbox_hosting').on('click', function() {
        $('.checkbox_hosting').prop('checked', this.checked);
    });
    $('.checkbox_hosting').on('click', function() {
        $('#check_all_checkbox_hosting').prop('checked', $('.checkbox_hosting:checked')
            .length === $('.checkbox_hosting').length);
    });
});
</script>

<?php
require_once(__DIR__.'/Footer.php');
?>
