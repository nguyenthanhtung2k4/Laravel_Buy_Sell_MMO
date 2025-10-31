<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Quản Lý Domain | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách Domain'
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-globe"></i> Danh sách Domain</h1>
            <a href="<?=BASE_URL('admin/add-domain')?>" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Thêm Domain</a>
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive table-wrapper">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Hình ảnh</th>
                                <th class="text-center">Tên miền</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $list_domain = $TN->get_list("SELECT * FROM `tbl_list_domain` ORDER BY id DESC");
                        foreach ($list_domain as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?=$row['id']?></td>
                                <td class="text-center">
                                    <img src="<?=$row['image']?>" alt="<?=$row['name']?>" width="60" height="36" style="object-fit:contain;">
                                </td>
                                <td class="text-center"><strong>.<?=$row['name']?></strong></td>
                                <td class="text-center"><?=format_cash($row['price'])?>đ</td>
                                <td class="text-center">
                                    <?=($row['value'] == 'on') ? '<span class="text-success">Hiển thị</span>' : '<span class="text-danger">Ẩn</span>'?>
                                </td>
                                <td class="text-center">
                                    <a href="<?=BASE_URL('admin/edit-domain/'.$row['id'])?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Sửa</a>
                                    <a href="javascript:void(0);" onclick="RemoveDomain(<?=$row['id']?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function RemoveDomain(id) {
    cuteAlert({
        type: "question",
        title: "Xác nhận xóa domain",
        message: "Bạn có chắc muốn xóa domain ID " + id + " không?",
        confirmText: "Xóa",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('ajaxs/admin/removeDomain.php')?>",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(response) {
                    if (response.status === 'success') {
                        showMessage(response.msg, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage(response.msg, "error");
                    }
                }
            });
        }
    });
}
</script>

<?php require_once(__DIR__.'/Footer.php'); ?>
