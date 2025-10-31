<?php
$title = 'Danh Sách VPS - ' . $TN->site('title');
$body['header'] = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '
    
';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
//CheckLogin();
?>

<main>
    <div class="w-breadcrumb-area">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">VPS</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Các gói dịch vụ
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <section class="py-110">
        <div class="container">
            <div class="row">
                <?php foreach($TN->get_list("SELECT * FROM `tbl_list_vps` ORDER BY id ASC") as $row) { ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="price-card aos aos-init aos-animate">
                            <div class="price-title">
                                <div class="plan-type">
                                    <h3><?=$row['namevps']?></h3>
                                </div>
                                <div class="amt-item">
                                    <h2><?= format_cash($row['price']); ?></h2>
                                    <p>Tháng</p>
                                </div>
                            </div>
                            <div class="price-features">
                                <h6>Includes</h6>
                                <ul>
                                    <li><span><i class="bx bx-check-double"></i></span>CPU: <?=$row['cpu']?> Core</li>
                                    <li><span><i class="bx bx-check-double"></i></span>RAM: <?=$row['ram']?> GB</li>
                                    <li><span><i class="bx bx-check-double"></i></span>DISK: <?=$row['disk']?> GB SSD</li>
                                    <li><span><i class="bx bx-check-double"></i></span>IP RIÊNG: <?=$row['ip']?></li>
                                    <li><span><i class="bx bx-check-double"></i></span>Băng thông: <?=$row['bandwidth']?></li>
                                    <li><span><i class="bx bx-check-double"></i></span>Hệ điều hành: Windows/Linux</li>
                                </ul>
                            </div>
                            <div class="price-btn">
                                <a href="/client/vps/<?=$row['id']?>" class="btn-primary">Chọn gói<i class="feather-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </section>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>