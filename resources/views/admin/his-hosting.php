<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Lịch Sử Mua Hosting | ' . $TN->site('title');
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hosting</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            LỊCH SỬ MUA HOSTING
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Tên hosting</th>
                                        <th>Tên miền</th>
                                        <th>Số tiền</th>
                                        <th>Ngày mua</th>
                                        <th>Hết hạn</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    foreach($TN->get_list(" SELECT * FROM `tbl_his_hosting` ORDER BY id DESC ") as $row){
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=getUser($row['user_id'], 'username');?></a></td>
                                        <td><?=$row['name'];?></td>
                                        <td><?=$row['domain'];?></td>
                                        <td class="text-right"><span class="badge bg-danger-gradient"><?=format_cash($row['price']);?>đ</span></td>
                                        <td><?=$row['create_date'];?></td>
                                        <td><?=$row['end_day'];?></td>
                                        <td><?= status_hosting($row['status']); ?></td>
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