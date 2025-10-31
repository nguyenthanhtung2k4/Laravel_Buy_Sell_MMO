<?php
if(isset($_GET['id']))
{
     $rows = $TN->get_row(" SELECT * FROM `tbl_list_code` WHERE `user_id` = '".check_string($_GET['id'])."'  ");
    $row = $TN->get_row(" SELECT * FROM `users` WHERE `id` = '".check_string($_GET['id'])."'  ");
    if(!$row)
    {
        header("Location: /");
exit;
    }
    }
else
{
    header("Location: /");
exit;
}
if ($row['seller'] != 1) {
    echo "<script>alert('Người này chưa được cấp quyền seller.'); window.location.href='/';</script>";
    exit;
}
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
?>
<link rel="stylesheet" href="/assets/css/styles.css">
<main>
    <div class="w-breadcrumb-area">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết người bán</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Chi tiết người bán</h2>
                </div>
            </div>
        </div>
    </div>

    <section class="py-110">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <aside class="freelancer-details-sidebar d-flex flex-column gap-4">
                        <div class="freelancer-sidebar-card p-4 rounded-4 bg-white position-relative shadow-sm">
                            <div class="freelancer-sidebar-card-header d-flex flex-column justify-content-center align-items-center py-4">
                                <div class="custom-reletive">
                                    <img class="freelancer-avatar rounded-circle mb-4" src="/<?= !empty($row['profile_picture']) ? $row['profile_picture'] : 'assets/images/avt.png'; ?>" alt="Avatar" />
                                    <span class="online-indicator1"></span>
                                </div>
                                <h3 class="fw-bold freelancer-name text-dark-300 mb-2 relative">
                                    <?=$row['name'];?>                                    
                                    <button class="varified-badge">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#00BFFF">
                                                <path d="M10.007 2.10377C8.60544 1.65006 7.08181 2.28116 6.41156 3.59306L5.60578 5.17023C5.51004 5.35763 5.35763 5.51004 5.17023 5.60578L3.59306 6.41156C2.28116 7.08181 1.65006 8.60544 2.10377 10.007L2.64923 11.692C2.71404 11.8922 2.71404 12.1078 2.64923 12.308L2.10377 13.993C1.65006 15.3946 2.28116 16.9182 3.59306 17.5885L5.17023 18.3942C5.35763 18.49 5.51004 18.6424 5.60578 18.8298L6.41156 20.407C7.08181 21.7189 8.60544 22.35 10.007 21.8963L11.692 21.3508C11.8922 21.286 12.1078 21.286 12.308 21.3508L13.993 21.8963C15.3946 22.35 16.9182 21.7189 17.5885 20.407L18.3942 18.8298C18.49 18.6424 18.6424 18.49 18.8298 18.3942L20.407 17.5885C21.7189 16.9182 22.35 15.3946 21.8963 13.993L21.3508 12.308C21.286 12.1078 21.286 11.8922 21.3508 11.692L21.8963 10.007C22.35 8.60544 21.7189 7.08181 20.407 6.41156L18.8298 5.60578C18.6424 5.51004 18.49 5.35763 18.3942 5.17023L17.5885 3.59306C16.9182 2.28116 15.3946 1.65006 13.993 2.10377L12.308 2.64923C12.1078 2.71403 11.8922 2.71404 11.692 2.64923L10.007 2.10377ZM6.75977 11.7573L8.17399 10.343L11.0024 13.1715L16.6593 7.51465L18.0735 8.92886L11.0024 15.9999L6.75977 11.7573Z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </h3>
                            </div>

                            <div class="d-flex gap-4 justify-content-between sidebar-rate-card bg-offWhite p-4 rounded-4">
                                <div>
                                    <p class="text-dark-300 fw-medium">Sản phẩm</p>
                                    <p class="text-dark-200"><?= format_cash($TN->get_row("SELECT SUM(user_id) as total FROM tbl_list_code")['total'] ?? 0) ?></p>
                                </div>
                                <div>
                                    <p class="text-dark-300 fw-medium">Đã bán</p>
                                    <p class="text-dark-200"><?= format_cash($TN->get_row("SELECT SUM(sold) as total FROM tbl_list_code")['total'] ?? 0) ?></p>
                                </div>
                            </div>

                            <ul class="py-4">
                                <li class="py-1 d-flex justify-content-between">
                                    <p class="text-dark-200">Địa chỉ:</p>
                                    <p class="text-dark-300 fw-medium"><?=$row['address'];?></p>
                                </li>
                                <li class="py-1 d-flex justify-content-between">
                                    <p class="text-dark-200">Thành viên từ:</p>
                                    <p class="text-dark-300 fw-medium"><?=format_date($row['create_date']);?></p>
                                </li>
                                <li class="py-1 d-flex justify-content-between">
                                    <p class="text-dark-200">Giới tính:</p>
                                    <p class="text-dark-300 fw-medium">Nam</p>
                                </li>
                            </ul>
                        </div>

                        <div class="freelancer-sidebar-card p-4 rounded-4 bg-white shadow-sm">
                            <div class="freelancer-single-info pb-4">
                                <h4 class="freelancer-sidebar-title text-dark-300 fw-semibold">Giới thiệu về bản thân</h4>
                                <p class="text-dark-200 fs-6">
                                    <?=$row['description'];?>
                                </p>
                            </div>
                            <div class="freelancer-single-info py-4">
                                <h4 class="freelancer-sidebar-title text-dark-300 fw-semibold">Kỹ năng</h4>
                                <div class="freelancer-skills d-flex flex-wrap gap-3">
                                    <?php
                                        $skills = explode(',', str_replace(',', ',', $row['skill']));
                                        foreach ($skills as $skill) {
                                            echo '<span class="single-skill">' . trim($skill) . '</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
                    <div class="bg-white d-flex gap-3 p-4 freelancer-tab mb-4">
                        <div class="filters-btns d-flex flex-wrap align-items-center gap-3">
                            <button class="service-filter-btn active" data-filter=".category1">Tất cả</button>
                            <?php foreach($TN->get_list("SELECT * FROM `sevice_code` WHERE `status` = '1' ORDER BY id ASC") as $row) { ?>
                                <button class="service-filter-btn" data-filter=".category<?=$row['id']?>"><?=$row['name']?></button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div id="loading-indicator" class="loading-indicator"><div class="spinner"></div></div>
                        <?php
                        $user_id = $rows['user_id'];
                        $products = $TN->get_list("SELECT * FROM `tbl_list_code` WHERE `user_id` = '{$user_id}' ORDER BY `ghim` DESC, `id` DESC");
                        foreach($products as $row) {
                        ?>
                        <article class="col-xl-4 col-md-6 grid-item category1 category<?=$row['sevice_code']?>">
                            <div class="gigs-grid">
                                <div class="gigs-img">
                                    <a href="/client/view-code/<?=$row['code']?>">
                                        <img src="/assets/images/lazyload.gif" data-src="<?=$row['images'];?>" class="lazyLoad w-100" height="180" alt="<?=$row['name'];?>">
                                    </a>
                                    <?php if($row['ghim'] == 1): ?>
                                        <div class="card-overlay-badge">
                                            <a href="/client/view-code/<?=$row['code']?>">
                                                <span class="badge bg-danger"><i class="fa-solid fa-meteor"></i>Ghim</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <input id="token" type="hidden" value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">
                                    <div class="fav-selection">
                                        <a href="javascript:void(0);" class="fav-icon" data-product-id="<?=$row['id']?>">
                                            <i class="fa<?=$isFavorite ? '-solid' : '-regular'?> fa-heart"></i>
                                        </a>
                                    </div>
                                    <div class="user-thumb">
                                        <a href="/client/seller/<?=$row['user_id']?>">
                                            <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>" alt="User">
                                        </a>
                                    </div>
                                </div>

                                <div class="gigs-content">
                                    <div class="gigs-info">
                                        <a href="#" class="badge bg-primary-light">Php Script</a>
                                        <div class="star-rate">
                                            <span><i class="fa-solid fa-star"></i><span id="averageRating" class="me-1">0</span> (0 Reviews)</span>
                                        </div>
                                    </div>
                                    <div class="gigs-title">
                                        <h3><a href="/client/view-code/<?=$row['code']?>" class="truncate-2-lines"><?=$row['name'];?></a></h3>
                                    </div>
                                    <div class="gigs-card-footer">
                                        <div class="gigs-share">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?=BASE_URL('client/view-code/');?><?=$row['code']?>">
                                                <i class="fa fa-share-alt"></i>
                                            </a>
                                            <span class="badge">
                                                <?php
                                                    $timestamp = $row['create_date'];
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
                                        <h5><?=format_cash($row['price'] - $row['price'] * $row['sale'] /100) ?>đ</h5>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>