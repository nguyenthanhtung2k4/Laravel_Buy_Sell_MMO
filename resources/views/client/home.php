<?php
$title = '' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
//CheckLogin();
if (isset($row['id'])) {
    $isFavorite = $TN->get_row("SELECT * FROM `favorite` WHERE `user_id` = '{$getUser['id']}' AND `product_id` = '{$row['id']}'");
} else {
    $isFavorite = null;
}



// Định nghĩa mapping cho Category để dùng trong HTML
$categories = [
    '1' => 'TOOL',
    '2' => 'BOT',
    '3' => 'WEB',
    '4' => 'HACK',
];


?>
<main>
    <!-- <div class="breadcrumb-bar breadcrumb-bar-info">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-12 col-12">

                    <div class="slide-title-wrap">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="slider-title">
                                    <h2>Các danh mục phổ biến của chúng tôi</h2>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="owl-nav service-nav nav-control nav-top"></div>
                            </div>
                        </div>
                    </div>

                    <div class="service-sliders owl-carousel">

                        <div class="service-box">
                            <div class="service-info">
                                <span class="service-icon">
                                    <img src="/assets/images/code.webp" alt="icon">
                                </span>
                                <div class="servive-name">
                                    <h5><a href="/client/list-code">Mã Nguồn</a></h5>
                                    <p><?= format_cash($TN->num_rows("SELECT * FROM `tbl_list_code`")) ?> Sản phẩm</p>
                                </div>
                            </div>
                            <a href="/client/list-code"><i class="feather-arrow-up-right"></i></a>
                        </div>

                        <div class="service-box">
                            <div class="service-info">
                                <span class="service-icon">
                                    <img src="/assets/images/vps.svg" alt="icon">
                                </span>
                                <div class="servive-name">
                                    <h5><a href="/client/hosting">Hosting</a></h5>
                                    <p><?= format_cash($TN->num_rows("SELECT * FROM `tbl_list_hosting`")) ?> Gói</p>
                                </div>
                            </div>
                            <a href="/client/hosting"><i class="feather-arrow-up-right"></i></a>
                        </div>

                        <div class="service-box">
                            <div class="service-info">
                                <span class="service-icon">
                                    <img src="/assets/images/vps.svg" alt="icon">
                                </span>
                                <div class="servive-name">
                                    <h5><a href="/client/cloudvps">Cloud VPS</a></h5>
                                    <p><?= format_cash($TN->num_rows("SELECT * FROM `tbl_list_vps`")) ?> Gói</p>
                                </div>
                            </div>
                            <a href="/client/cloudvps"><i class="feather-arrow-up-right"></i></a>
                        </div>

                        <div class="service-box">
                            <div class="service-info">
                                <span class="service-icon">
                                    <img src="/assets/images/domains.png" alt="icon"> 
                                </span>
                                <div class="servive-name">
                                    <h5><a href="/client/reg-domain">Tên Miền</a></h5>
                                    <p><?= format_cash($TN->num_rows("SELECT * FROM `tbl_list_domain`")) ?> Miền</p>
                                </div>
                            </div>
                            <a href="/client/reg-domain"><i class="feather-arrow-up-right"></i></a>
                        </div>

                        <div class="service-box">
                            <div class="service-info">
                                <span class="service-icon">
                                    <img src="/assets/images/cron_jobs.png" alt="icon">
                                </span>
                                <div class="servive-name">
                                    <h5><a href="/client/cronjob">Dịch Vụ Cron</a></h5>
                                    <p><?= format_cash($TN->num_rows("SELECT * FROM `server_cron`")) ?> Server</p>
                                </div>
                            </div>
                            <a href="/client/cronjob"><i class="feather-arrow-up-right"></i></a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div> -->
    <br>
    <br>
    <section class="services-filter py-5">
        <div class="container">
            <div class="row mb-40 justify-content-between align-items-end">
                <div class="col-auto">
                    <h2 class="fw-bold section-title">Sản phẩm nổi bật</h2>
                    <p class="section-desc">
                        Dịch vụ tốt nhất cho công việc của bạn
                    </p>
                </div>
                <div class="col-auto mt-30 mt-xl-0">
                    <div class="filters-btns d-flex flex-wrap align-items-center gap-3">
                        <button class="service-filter-btn active" data-filter=".category1">
                            Tất cả
                        </button>
                        <?php foreach ($TN->get_list("SELECT * FROM `sevice_code` WHERE `status` = '1' ORDER BY id ASC") as $row) { ?>
                            <button class="service-filter-btn" data-filter=".category<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="loading-indicator" class="loading-indicator">
                    <div class="spinner"></div>
                </div>
                <?php
                $list_code = $TN->get_list("SELECT * FROM `tbl_list_code` WHERE `status` = '1' ORDER BY `ghim` DESC, `id` DESC");

                $limit = 8;
                $count = 0;

                foreach ($list_code as $row) {
                    if ($count >= $limit)
                        break;
                    $count++;
                    ?>
                    <article class="col-xl-3 col-lg-4 col-md-6 mb-4 grid-item category1 category<?= $row['sevice_code'] ?>">
                        <div class="gigs-grid">
                            <div class="gigs-img">
                                <div class="">
                                    <a href="/client/view-code/<?= $row['code'] ?>">
                                        <img src="/assets/images/lazyload.gif" data-src="<?= $row['images']; ?>"
                                            class="lazyLoad w-100" height="180" alt="<?= $row['name']; ?>">
                                    </a>
                                </div>
                                <?php if ($row['ghim'] == 1) { ?>
                                    <div class="card-overlay-badge">
                                        <a href="/client/view-code/<?= $row['code'] ?>"><span class="badge bg-danger"><i
                                                    class="fa-solid fa-meteor"></i>Ghim</span></a>
                                    </div>
                                <?php } ?>
                                <input id="token" type="hidden"
                                    value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">
                                <div class="fav-selection">
                                    <a href="javascript:void(0);" class="fav-icon" data-product-id="<?= $row['id'] ?>">
                                        <i class="fa<?= $isFavorite ? '-solid' : '-regular' ?> fa-heart"></i>
                                    </a>
                                </div>
                                <div class="user-thumb">
                                    <a href="/client/seller/<?= $row['user_id'] ?>">
                                        <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>"
                                            alt="User">
                                    </a>
                                </div>
                            </div>
                            <div class="gigs-content">
                                <div class="gigs-info">
                                    <!-- <a href="#" class="badge bg-primary-light">Php Script</a> -->
                                    <span class="badge bg-primary-light"><?= $categories[$row['sevice_code']] ?? 'Khác' ?></span>
                                    <!-- <div class="star-rate">
                                        <span><i class="fa-solid fa-star"></i><span id="averageRating" class="me-1">0</span>
                                            (0 Reviews)</span>
                                    </div> -->
                                </div>
                                <div class="gigs-title">
                                    <h3>
                                        <a href="/client/view-code/<?= $row['code'] ?>" class="truncate-2-lines">
                                            <?= $row['name']; ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="gigs-card-footer">
                                    <div class="gigs-share">
                                        <a
                                            href="https://www.facebook.com/sharer/sharer.php?u=<?= BASE_URL('client/view-code/'); ?><?= $row['code'] ?>">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        <span class="badge">
                                            <?php $timestamp = $row['create_date'];
                                            $now = time();
                                            $diff = $now - $timestamp;

                                            if ($diff < 0) {
                                                echo 'Trong tương lai';
                                            } elseif ($diff < 60) {
                                                echo $diff . ' giây trước';
                                            } elseif ($diff < 3600) {
                                                echo floor($diff / 60) . ' phút trước';
                                            } elseif ($diff < 86400) {
                                                echo floor($diff / 3600) . ' giờ trước';
                                            } elseif ($diff < 30 * 86400) {
                                                echo floor($diff / 86400) . ' ngày trước';
                                            } else {
                                                echo date('d/m/Y', $timestamp);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <h5><?= format_cash($row['price'] - $row['price'] * $row['sale'] / 100) ?>đ</h5>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
            <div class="text-center mt-4">
                <a href="/client/list-code" class="btn btn-primary">Xem tất cả mã nguồn</a>
            </div>
        </div>
    </section>
    <!-- <div class="gigs-card-footer">
        <div class="container">
            <div class="row mb-40 justify-content-between align-items-end">
                <div class="col-auto">
                    <h2 class="fw-bold section-title">Sản Phẩm Hosting</h2>
                    <p class="section-desc">
                        Cloud Hosting Giá Rẻ
                    </p>
                </div>

                <section><br>
                    <div class="container">
                        <div class="row">
                            <?php foreach ($TN->get_list("SELECT * FROM `tbl_list_hosting` ORDER BY id ASC") as $row) { ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="price-card aos aos-init aos-animate">
                                        <div class="price-title">
                                            <div class="plan-type">
                                                <h3><?= $row['name'] ?></h3>
                                            </div>
                                            <div class="amt-item">
                                                <h2><?= format_cash($row['price']); ?></h2>
                                                <p>Tháng</p>
                                            </div>
                                        </div>
                                        <div class="price-features">
                                            <h6>Includes</h6>
                                            <ul>
                                                <li><span><i class="bx bx-check-double"></i></span>Dung lượng:
                                                    <?= format_cash($row['dungluong']); ?> MB</li>
                                                <li><span><i class="bx bx-check-double"></i></span>Băng Thông:
                                                    <?= $row['bangthong'] ?></li>
                                                <li><span><i class="bx bx-check-double"></i></span>Miễn Phí Chứng Chỉ SSL
                                                </li>
                                                <li><span><i class="bx bx-check-double"></i></span>Miền Khác:
                                                    <?= ($row['mienkhac'] === 'unlimited') ? 'Không giới hạn' : $row['mienkhac'] ?>
                                                </li>
                                                <li><span><i class="bx bx-check-double"></i></span>Miền Bí Danh:
                                                    <?= ($row['mienbidanh'] === 'unlimited') ? 'Không giới hạn' : $row['mienbidanh'] ?>
                                                </li>
                                                <li><span><i class="bx bx-check-double"></i></span>Giao Diện:
                                                    <?= $row['cpmod'] ?></li>
                                                <li><span><i class="bx bx-check-double"></i></span>Vị Trị Máy Chủ: Việt Nam
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="price-btn">
                                            <a href="/client/hostings/<?= $row['id'] ?>" class="btn-primary">Chọn gói<i
                                                    class="feather-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div> -->

</main>

<?php if ($TN->site('status_noti') == 1): ?>

    <div class="modal new-modal fade" id="modal_notification" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông báo</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body service-modal">
                    <?= $TN->site('notification'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="dontShowAgainBtn">Đóng trong 2 giờ</button>
                </div>
            </div>
        </div>
    </div>

<?php endif ?>

<?php require_once __DIR__ . '/footer.php'; ?>