<?php
$title = 'Sản Phẩm Yêu Thích | ' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
if (isset($row['id'])) {
    $isFavorite = $TN->get_row("SELECT * FROM `favorite` WHERE `user_id` = '{$getUser['id']}' AND `product_id` = '{$row['id']}'");
} else {
    $isFavorite = null;
}
?>
<?php require_once('sidebar.php');?>

       <div class="col-md-12">
            <h3 class="text-24 fw-bold text-dark-300 mb-2">SẢN PHẨM YÊU THÍCH</h3>
<?php
$list = $TN->get_list("
    SELECT f.*, p.*
    FROM favorite f
    JOIN tbl_list_code p ON f.product_id = p.id
    WHERE f.user_id = '" . $getUser['id'] . "'
    ORDER BY f.id DESC
");
?>

<div class="row">
    <?php if (!empty($list)) { ?>
        <?php foreach ($list as $row) { ?>
                <article class="col-xl-3 col-lg-4 col-md-6 mb-4 grid-item category1 category<?=$row['sevice_code']?>">
                    <div class="gigs-grid">
                        <div class="gigs-img">
                            <div class="">
                                <a href="/client/view-code/<?=$row['code']?>">
                                    <img src="/assets/images/lazyload.gif" data-src="<?=$row['images'];?>" class="lazyLoad w-100" height="180" alt="<?=$row['name'];?>">
                                </a>
                            </div>
                            <?php if ($row['ghim'] == 1) { ?>
                                <div class="card-overlay-badge">
                                    <a href="/client/view-code/<?=$row['code']?>"><span class="badge bg-danger"><i class="fa-solid fa-meteor"></i>Ghim</span></a>
                                </div>
                            <?php } ?>
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
                                <h3>
                                    <a href="/client/view-code/<?=$row['code']?>" class="truncate-2-lines">
                                        <?=$row['name'];?>
                                    </a>
                                </h3>
                            </div>
                            <div class="gigs-card-footer">
                                <div class="gigs-share">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?=BASE_URL('client/view-code/');?><?=$row['code']?>">
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
                                <h5><?=format_cash($row['price'] - $row['price'] * $row['sale'] / 100) ?>đ</h5>
                            </div>
                        </div>
                    </div>
                </article>
                <?php } ?>
                <?php } else { ?>
                <div class="empty-state">
                    <svg width="184" height="152" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(24 31.67)">
                                <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse>
                                <path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path>
                                <path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                                <path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path>
                                <path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path>
                            </g>
                            <path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path>
                            <g transform="translate(149.65 15.383)" fill="#FFF">
                                <ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse>
                                <path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path>
                            </g>
                        </g>
                    </svg>
                    <p>Không có dữ liệu</p>
                </div>
                <?php } ?>
                                                         
                    </div>
                    <div class="d-flex justify-content-center">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>