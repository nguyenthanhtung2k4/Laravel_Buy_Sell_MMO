<?php
if(isset($_GET['code']))
{
    $row = $TN->get_row(" SELECT * FROM `tbl_list_code` WHERE `code` = '".check_string($_GET['code'])."'  ");
    if(!$row)
    {
        header("Location: /");
exit;
    }
    $TN->cong("tbl_list_code", "view", 1, " `id` = '".$row['id']."' ");
}
else
{
    header("Location: /");
exit;
}
$title = $row['name'] . ' | ' . $TN->site('title');
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
?>
   
<main>
    <div class="w-breadcrumb-area">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/client/list-code">List code</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Php Script</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        <?=$row['name'];?>
                    </h2>
                </div>
                <div class="col-lg-5 col-12">
                    <ul class="breadcrumb-links">
                        <li>
                            <a href="javascript:void(0);" class="fav-icon" data-product-id="36">
                                <span><i class="fa-regular fa-heart"></i></span> Yêu thích
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=BASE_URL('client/view-code/');?><?=$row['code']?>"><span><i class="fa-brands fa-facebook"></i></span>Chia sẻ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <section class="py-110">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="slider-card">
                        <div class="slider service-slider">
                            <?php
                            $lines = explode("\n", $row['list_images']);
                            if (!empty($lines)) {
                                foreach ($lines as $line) { ?>
                                    <div class="service-img-wrap">
                                        <img src="<?= trim($line); ?>" alt="Slider Image">
                                    </div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="slider slider-nav-thumbnails">
                        <?php
                        if (!empty($lines)) {
                            foreach ($lines as $line) { ?>
                                <div>
                                    <img src="<?= trim($line); ?>" alt="Thumbnail Image">
                                </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <input type="hidden" value="<?=$row['id']?>" id="id_product">
                    <div class="mt-40">
                        <div class="service_details legal-content">
                            <div class="content-details service-wrap">
                                <?=$row['intro'];?>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-xl-3 col-lg-4 mt-30 mt-xl-0">
                    <aside class="d-flex flex-column gap-4">
                        <div class="service-widget">
                            <div class="service-amt d-flex align-items-center justify-content-between">
                                <p>Giá bán</p>
                                <h2><?=format_cash($row['price'] - $row['price'] * $row['sale'] /100) ?>đ</h2>
                            </div>
                            <ul class="mb-4">
                                        <?php
                                        $atm = $row['content'];
                                        $delimiters = array(",");
                                        $atm = str_replace($delimiters, $delimiters[0], $atm);
                                        $arrKeyword= explode($delimiters[0], $atm);
                                        foreach ($arrKeyword as $key)
                                        {
                                           echo '<li class="fs-6 d-flex align-items-center gap-3 text-dark-200">
                                           <i class="fa fa-check"></i>'.$key.'
                                           </li>';
                                        }
                                        ?>
                                        
                                                            </ul>
                            <a href="#" data-bs-toggle="modal"
                                data-bs-target="#stripePayment" class="btn btn-primary w-100"><i class="fa fa-shopping-cart"></i> Thanh Toán</a>
                            <div class="row gx-3 row-gap-3">

                                <div class="col-xl-6 col-lg-6 col-sm-4 col-6">
                                    <div class="buy-box">
                                        <i class="feather-cloud"></i>
                                        <p>Tổng số lượt bán</p>
                                        <h6><?=$row['sold'];?></h6>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-4 col-6">
                                    <div class="buy-box">
                                        <i class="feather-eye"></i>
                                        <p>Tổng số lượt xem</p>
                                        <h6><?=$row['view'];?></h6>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="service-wrap tags-widget">
                            <h3>Thẻ liên quan</h3>
                            <ul class="tags">
                                <ul>
                                    <li>
                                        <a href="/client/list-code?hashtag=Html">#Web</a>
                                    </li>
                                    <li>
                                        <a href="/client/list-code?hashtag=Php">#mmo</a>
                                    </li>
                                    <li>
                                        <a href="/client/list-code?hashtag=Code">#Code</a>
                                    </li>
                                    <li>
                                        <a href="/client/list-code?hashtag=Laravel">#tds</a>
                                    </li>
                                    <li>
                                        <a href="/client/list-code?hashtag=Css">#ttc</a>
                                    </li>
                                    <li>
                                        <a href="/client/list-code?hashtag=Javascript">#golike</a>
                                    </li>
                                </ul>
                            </ul>
                        </div>
                        <div class="service-widget member-widget">
                            <div class="user-details">
                                <div class="user-img">
                                    <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>" alt="img">
                                </div>
                                <div class="user-info">
                                    <h5><span class="me-2"><?=getUser($row['user_id'], 'name');?></span>
                                                                                    <?=online(getUser($row['user_id'], 'time_session'));?>
                                                                            </h5>
                                </div>
                            </div>
                            <ul class="member-info">
                                <li>
                                    Địa chỉ
                                    <span><?=getUser($row['user_id'], 'address');?></span>
                                </li>
                                <li>
                                    Tổng số sản phẩm
                                    <span><?= format_cash($TN->get_row("SELECT SUM(user_id) as total FROM tbl_list_code")['total'] ?? 0) ?></span>
                                </li>
                                <li>
                                    Đã bán
                                    <span><?= format_cash($TN->get_row("SELECT SUM(sold) as total FROM tbl_list_code")['total'] ?? 0) ?></span>
                                </li>

                            </ul>
                            <a href="/client/seller/<?=$row['user_id'];?>" class="btn btn-primary mb-0 w-100">
                                Xem cửa hàng
                            </a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Details End -->
</main>

<div class="modal new-modal fade" id="stripePayment" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thanh toán</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body service-modal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="order-status">
                            <div class="order-item">
                                <div class="order-img">
                                    <img src="<?=$row['images'];?>" alt="img">
                                </div>
                                <div class="order-info">
                                    <h5><?=$row['name'];?></h5>
                                    <ul>
                                        <li>ID : #<?=$row['id']?></li>
                                        <li>Ngày cập nhật : <?=format_date($row['create_date']);?></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h6 class="title">Người bán</h6>
                            <div class="user-details">
                                <div class="user-img">
                                    <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>" alt="img">
                                </div>
                                <div class="user-info">
                                    <h5><?=getUser($row['user_id'], 'name');?> <span class="location"><?=getUser($row['user_id'], 'address');?></span></h5>

                                </div>
                            </div>
                            <h6 class="title">Chi tiết thanh toán</h6>
                            <div class="detail-table table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>Mã giảm giá</td>
                                        <td>
                                            <input type="text" class="form-control shadow-none" id="coupon" name="coupon" onchange="totalPayment()" onkeyup="totalPayment()" placeholder="Nhập mã giảm giá">
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="1">Tổng tiền</th>
                                            <th class="text-primary"><?=format_cash($row['price'] - $row['price'] * $row['sale'] /100) ?>đ</b></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="modal-btn">
                                <div class="row gx-2">
                                    <div class="col-6">
                                        <a href="#" data-bs-dismiss="modal" class="btn btn-secondary w-100 justify-content-center">Đóng</a>
                                    </div>
                                    <div class="col-6">
                                    <input id="token" type="hidden" value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">
                                        <button type="button" id="paymentCart" class="btn btn-primary w-100">
                                            Thanh Toán 
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $("#paymentCart").on("click", function() {
    $('#paymentCart').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

    var formData = {
        action: 'pay',
        id_product: $('#id_product').val(),
        token: $('#token').val(),
    };

    $.ajax({
        url: "/ajaxs/client/buy-code.php",
        method: "POST",
        dataType: "JSON",
        data: formData,
        success: function(response) {
            if (response.status === '2') {
                showMessage(response.msg, 'success');
                setTimeout(function() {
                    location.href = '<?=BASE_URL('client/user-history-code');?>';
                }, 500);
            } else {
                showMessage(response.msg, 'error');
            }
            $('#paymentCart').html('Thanh Toán ').prop('disabled', false);
        },
        error: function() {
            showMessage("Không thể xử lý yêu cầu của bạn", 'error');
            $('#paymentCart').html('Thanh Toán ').prop('disabled', false);
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<script>
    $(function() {
        $("img.lazyLoad").lazyload({
            effect: "fadeIn"
        });
    });
    function displayStars(averageRating) {
        const starsContainer = document.querySelector('.rating');
        const averageRatingElement = document.getElementById('averageRating');
        const roundedRating = Math.round(averageRating);

        averageRatingElement.textContent = averageRating.toFixed(1);

        const allStars = starsContainer.querySelectorAll('input[name="rating"]');
        allStars.forEach(star => (star.checked = false));

        const selectedStar = starsContainer.querySelector(`#stars${roundedRating}`);
        if (selectedStar) {
            selectedStar.checked = true;
        }
    }

    $(document).ready(function() {
        $('.service-filter-btn').on('click', function() {
            $('#loading-indicator').addClass('show');
            setTimeout(function() {
                $('#loading-indicator').removeClass('show');
            }, 300);
        });
    });
    $(document).ready(function () {
        $('.service-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            rtl: true,
            autoplay: true,
            autoplaySpeed: 2000,
            asNavFor: '.slider-nav-thumbnails'
        });
        $('.slider-nav-thumbnails').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.service-slider',
            dots: false,
            centerMode: true,
            focusOnSelect: true,
            rtl: true
        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>