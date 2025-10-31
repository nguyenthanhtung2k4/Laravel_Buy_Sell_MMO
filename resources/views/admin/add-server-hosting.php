<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm Server Hosting | ' . $TN->site('title');
$body = [
    'title' => 'Thêm Server Hosting'
];
$body['header'] = '';
$body['footer'] = '';

require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>

<?php
if(isset($_POST['Save']) && $getUser['level'] == 1)
{
    $isInsert = $TN->insert("server_hosting", [
        'name'         => xss($_POST['name']),
        'uname'        => xss($_POST['uname']),
        'backup'       => xss($_POST['backup']),
        'hostname'     => xss($_POST['hostname']),
        'whmusername'  => xss($_POST['whmusername']),
        'whmpassword'  => xss($_POST['whmpassword']),
        'ip'           => xss($_POST['ip']),
        'nameserver1'  => xss($_POST['nameserver1']),
        'nameserver2'  => xss($_POST['nameserver2']),
        'status'       => xss($_POST['status']),
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm thành công !")){location.href = "' . BASE_URL('admin/list-server-hosting') . '";}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <a type="button" class="btn btn-dark btn-sm me-1" href="<?=BASE_URL('admin/list-server-hosting')?>">
                    <i class="fa-solid fa-arrow-left"></i>
                </a> 
                Thêm Server Hosting
            </h1>
        </div>
        <form action="" method="POST">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Thông Tin Server Hosting</div>
                        </div>
                        <div class="card-body row gy-3">
                            <div class="col-sm-6">
                                <label class="form-label">Tên Server</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Mã định danh (uname)</label>
                                <input type="text" name="uname" class="form-control" placeholder="Package trong WHMC" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Hostname</label>
                                <input type="text" name="hostname" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Backup</label>
                                <select name="backup" class="form-control" required>
                                    <option value="Có">Có</option>
                                    <option value="Không">Không</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">WHM Username</label>
                                <input type="text" name="whmusername" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">WHM Password</label>
                                <input type="text" name="whmpassword" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">IP</label>
                                <input type="text" name="ip" class="form-control" required>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Nameserver 1</label>
                                <input type="text" name="nameserver1" class="form-control" required>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Nameserver 2</label>
                                <input type="text" name="nameserver2" class="form-control" required>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control" required>
                                    <option value="on">Hoạt động</option>
                                    <option value="off">Tạm dừng</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="Save" class="btn btn-primary"><i class="fa fa-plus"></i> Thêm Ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
require_once(__DIR__."/Footer.php");
?>
