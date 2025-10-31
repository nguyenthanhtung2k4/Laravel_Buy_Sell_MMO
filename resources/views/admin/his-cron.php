<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Lịch Sử Thuê Cron Job | ' . $TN->site('title');
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử thuê Cron Job</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            LỊCH SỬ THUÊ CRON JOB
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Server ID</th>
                                        <th>URL</th>
                                        <th>Interval (Second)</th>
                                        <th>Thời gian</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    foreach($TN->get_list(" SELECT * FROM `tbl_his_cron` ORDER BY id DESC ") as $row){
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=getUser($row['user_id'], 'username');?></td>
                                        <td><?=$row['id_server'];?></td>
                                        <td><?=$row['url'];?></td>
                                        <td><?=$row['second'];?> giây</td>
                                        <td><?=$row['time_his'];?></td>
                                        <td><?=$row['created_at'];?></td>
                                        <td><?=$row['expired_date'];?></td>
                                        <td><?= status_cron($row['status']); ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once(__DIR__.'/Footer.php');
?>
