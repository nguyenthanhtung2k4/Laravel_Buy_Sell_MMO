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
        
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tổng Quan Hệ Thống Kiểm Soát Truy Cập</h5>
            </div>
            <div class="card-body">
                <p class="lead">Chào mừng đến với hệ thống quản lý Token và Chữ Ký Số. Hệ thống này giúp giới hạn thiết bị truy cập và trả về mã thực thi một lần duy nhất.</p>
                
                <div class="alert alert-info" role="alert">
                    <strong>Mục tiêu:</strong> Đảm bảo chỉ các thiết bị được cấp quyền và trong giới hạn mới có thể nhận được mã lệnh thực thi (Payload).
                </div>

                <div class="row text-center mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light p-3 h-100">
                            <h5><i class="bi bi-key-fill"></i> Chữ Ký Số (Secret Key)</h5>
                            <p>Tạo và quản lý các khóa bí mật cùng với mã lệnh (Payload) sẽ được trả về.</p>
                            <a href="signature_manager.php" class="btn btn-sm btn-outline-primary">Quản Lý</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light p-3 h-100">
                            <h5><i class="bi bi-person-bounding-box"></i> Token Truy Cập</h5>
                            <p>Tạo Token giới hạn thiết bị truy cập (max 5) và liên kết với một Chữ Ký Số.</p>
                            <a href="token_generator.php" class="btn btn-sm btn-outline-primary">Tạo Token</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light p-3 h-100">
                            <h5><i class="bi bi-activity"></i> Kiểm Soát Trực Tiếp</h5>
                            <p>Theo dõi các yêu cầu truy cập đã được tạo và sử dụng trong thời gian gần nhất.</p>
                            <a href="live_control.php" class="btn btn-sm btn-outline-primary">Xem Live</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

