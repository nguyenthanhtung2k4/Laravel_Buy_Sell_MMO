<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Danh sách VPS | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách VPS'
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-server"></i> Danh sách VPS</h1>
            <a href="<?=BASE_URL('admin/add-vps')?>" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Thêm VPS mới</a>
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive table-wrapper mb-3">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Tên VPS</th>
                                <th class="text-center">Thông tin chi tiết</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $list_vps = $TN->get_list("SELECT * FROM `tbl_list_vps` ORDER BY id DESC");
                        foreach ($list_vps as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?=$row['id']?></td>
                                <td class="text-center"><?=$row['namevps']?></td>
                                <td>
                                    <ul style="padding-left: 18px; margin: 0;">
                                        <li><strong>CPU:</strong> <?=$row['cpu']?></li>
                                        <li><strong>RAM:</strong> <?=$row['ram']?> GB</li>
                                        <li><strong>Disk:</strong> <?=$row['disk']?> GB</li>
                                        <li><strong>IP:</strong> <?=$row['ip']?></li>
                                        <li><strong>Băng thông:</strong> <?=$row['bandwidth']?></li>
                                        <li><strong>Giá:</strong> <?=format_cash($row['price'])?>đ</li>
                                        <li><strong>Đã bán:</strong> <?=$row['daban']?> | <strong>Lượt xem:</strong> <?=$row['view']?></li>
                                        <li><strong>Trạng thái:</strong> <?=($row['status'] == 1 ? '<span class="text-success">Hiển thị</span>' : '<span class="text-danger">Ẩn</span>')?></li>
                                    </ul>
                                </td>
                                <td class="text-center">
                                    <a href="<?=BASE_URL('admin/edit-vps/').$row['id']?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Sửa</a>
                                    <a href="javascript:void(0);" onclick="RemoveVps(<?=$row['id']?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Xóa</a>
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
function RemoveVps(id) {
    cuteAlert({
        type: "question",
        title: "Xác nhận xóa VPS",
        message: "Bạn có chắc muốn xóa VPS ID " + id + " không?",
        confirmText: "Đồng ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('ajaxs/admin/removeVps.php')?>",
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
                        }, 2000);
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
