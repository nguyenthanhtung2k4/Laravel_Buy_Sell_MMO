<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

$title = 'Danh Sách License | ' . $TN->site('title');
$body = [
    'title' => 'Danh Sách License',
    'header' => '',
    'footer' => ''
];

require_once(__DIR__ . '/Header.php');
require_once(__DIR__ . '/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();

// Xử lý xóa license
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Bảo vệ chống SQL Injection
    $delete = $ketnoi->query("DELETE FROM `license_key` WHERE `id` = '$id'");

    if ($delete) {
        echo '<script>
            swal("Thành Công", "Xóa license thành công!", "success")
            .then(() => { location.href = "list-license.php"; });
        </script>';
    } else {
        echo '<script>
            swal("Lỗi", "Không thể xóa license. Vui lòng thử lại.", "error")
            .then(() => { location.href = "list-license.php"; });
        </script>';
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Danh Sách License</h1>
        </div>
        <div class="card custom-card">
            <div class="card-header">
                <h3 class="card-title">Danh Sách License</h3>
                <div class="text-right">
                    <a class="btn btn-primary btn-raised-shadow btn-wave btn-sm" href="add-license.php">
                        <i class="fas fa-plus-circle mr-1"></i> Thêm License
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">ID</th>
                            <th>Username</th>
                            <th>Miền</th>
                            <th>Mã License</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $ketnoi->query("SELECT * FROM `license_key`");
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['mien']); ?></td>
                                <td><?= htmlspecialchars($row['license']); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <a href="edit-license.php?id=<?= $row['id']; ?>" class="btn btn-default">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-default delete-btn">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Hộp thoại xác nhận xóa
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            swal({
                title: "Bạn có chắc không?",
                text: "Hành động này không thể hoàn tác!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = href;
                }
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css" rel="stylesheet" />
<?php require_once(__DIR__ . "/Footer.php"); ?>
