<?php
$title = 'Dasboard | ' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
?>
        <?php require_once('sidebar.php');?>

        <div class="row gy-4 dashboard-row-wrapper">
                    <div class="col-12">
                        <div class="row gy-3">

                            <div class="col-lg-3 col-sm-6">

                                <div class="p-3 d-flex align-items-center justify-content-between dashboard-widget border shadow-sm">
                                    <div>
                                        <h3 class="dashboard-widget-title text-dark-300">
                                            <?=format_cash($getUser['money']);?>đ
                                        </h3>
                                        <p class="text-18 text-dark-200">Số dư</p>
                                    </div>
                                    <div class="">
                                        <img src="/assets/images/money-bag.png" width="75" height="71" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="p-3 d-flex align-items-center justify-content-between dashboard-widget border shadow-sm">
                                    <div>
                                        <h3 class="dashboard-widget-title fw-bold text-dark-300">
                                            <?=format_cash($getUser['total_money']);?>đ
                                        </h3>
                                        <p class="text-18 text-dark-200">Tổng nạp</p>
                                    </div>
                                    <div class="">
                                        <img src="/assets/images/money-total.png" width="75" height="71" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="p-3 d-flex align-items-center justify-content-between dashboard-widget border shadow-sm">
                                    <div>
                                        <h3 class="dashboard-widget-title fw-bold text-dark-300">
                                            <?=format_cash($getUser['total_money']-$getUser['money']);?>đ
                                        </h3>
                                        <p class="text-18 text-dark-200">Đã chi</p>
                                    </div>
                                    <div class="">
                                        <img src="/assets/images/money-spending.png" width="75" height="71" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-sm-6">
                                <div class="p-3 d-flex align-items-center justify-content-between dashboard-widget border shadow-sm">
                                    <div>
                                        <h3 class="dashboard-widget-title fw-bold text-dark-300">
                                            <?php 
                                            if($getUser['level'] == '1'){
                                                echo 'Quản trị viên';
                                            } else {
                                                echo 'Thành viên';
                                                }
                                                ?>                                        
                                        </h3>
                                        <p class="text-18 text-dark-200">Cấp bậc</p>
                                    </div>
                                    <div class="">
                                        <img src="/assets/images/ranking-badge.png" width="75" height="71" />
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <?php if ($getUser['seller'] == '0' || empty($getUser['seller'])) { ?>
                    <div class="col-12">
                        <div class="card product-card">
                            <div class="card-body p-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <div class="text-center">
                                            <h3 class="text--base">Bạn có muốn bán hàng không?</h3>
                                            <p class="mb-3">Nếu bạn có sản phẩm hoặc dịch vụ độc đáo để cung cấp, hãy tham gia cộng đồng người bán của chúng tôi và giới thiệu sản phẩm của bạn đến nhiều đối tượng. Tận hưởng lợi ích khi tiếp cận khách hàng tiềm năng và phát triển doanh nghiệp của bạn cùng chúng tôi.</p>
                                            <a href="/client/user-author-form"
                                                class="btn btn-primary">Trở thành người bán hàng</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#purchase_date", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>