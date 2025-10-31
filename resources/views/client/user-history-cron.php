<?php
$title = 'Lịch Sử Thuê Cron | ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
?>
<?php require_once('sidebar.php'); ?>

<div class="row">
    <div class="col-md-12">
        <h3 class="text-24 fw-bold text-dark-300 mb-2">LỊCH SỬ THUÊ CRON</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="overflow-x-auto">
                    <div class="w-100">
                        <table id="cronTable" class="w-100 dashboard-table text-nowrap table">
                            <input id="token" type="hidden" value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">URL CRON</th>
                                    <th class="py-2 px-4">Giây chạy</th>
                                    <th class="py-2 px-4">Máy chủ</th>
                                    <th class="py-2 px-4">Ngày tạo</th>
                                    <th class="py-2 px-4">Hết hạn</th>
                                    <th class="py-2 px-4">Trạng thái</th>
                                    <th class="py-2 px-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data = $TN->get_list("SELECT * FROM `tbl_his_cron` WHERE `user_id` = '" . $getUser['id'] . "' ORDER BY `id` DESC");
                                if (count($data) > 0) {
                                    foreach ($data as $row) {
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($row['url']) . '</td>
                                            <td>' . htmlspecialchars($row['second']) . ' giây</td>
                                            <td>Server #' . htmlspecialchars($row['id_server']) . '</td>
                                            <td>' . $row['created_at'] . '</td>
                                            <td>' . $row['expired_date'] . '</td>
                                            <td>' . status_cron($row['status']) . '</td>
                                            <td>
' . (
    $row['status'] === 'ON'
    ? '<a href="javascript:;" onclick="stop('.$row['id'].')" class="btn btn-sm btn-danger">Dừng</a>'
    : ($row['status'] === 'STOP'
        ? '<a href="javascript:;" onclick="play('.$row['id'].')" class="btn btn-sm btn-success">Chạy</a>'
        : '<span class="btn btn-sm btn-secondary disabled">Hết hạn</span>'
    )
) . '
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state text-center p-4">
                                                <p class="text-muted">Không có dữ liệu cron nào được tìm thấy.</p>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</main>
<script type="text/javascript">
function play(id) {
    $.ajax({
        url: '/ajaxs/client/buy-cron.php',
        method: "POST",
        data: {
            action: 'play',
            token: $("#token").val(),
            id: id
        },
        dataType: "JSON",
        success: function (data) {
            if (data.status == '2') {
                Swal.fire({
                    title: 'Thành công!',
                    text: data.msg,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false,
                    didClose: () => {
                        location.reload();
                    }
                });
            } else {
                Swal.fire('Thất bại!', data.msg, 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error("Ajax Error:", xhr.responseText);
            Swal.fire('Lỗi!', 'Lỗi hệ thống! Vui lòng thử lại.', 'error');
        }
    });
}

function stop(id) {
    $.ajax({
        url: '/ajaxs/client/buy-cron.php',
        method: "POST",
        data: {
            action: 'stop',
            token: $("#token").val(),
            id: id
        },
        dataType: "JSON",
        success: function (data) {
            console.log("Response:", data);
            if (data.status == '2') {
                Swal.fire({
                    title: 'Thành công!',
                    text: data.msg,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false,
                    didClose: () => {
                        location.reload();
                    }
                });
            } else {
                Swal.fire('Thất bại!', data.msg, 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error("Ajax Error:", xhr.responseText);
            Swal.fire('Lỗi!', 'Lỗi hệ thống! Vui lòng thử lại.', 'error');
        }
    });
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#cronTable').DataTable();
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
