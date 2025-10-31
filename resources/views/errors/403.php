<?php
$title = '403 - ' . $TN->site('title');
$body['header'] = '
';
$body['footer'] = '
';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/../client/header.php';
require_once __DIR__ . '/../client/nav.php';
?>
<main>
    <div class="w-breadcrumb-area">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="403 Background">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">403</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Không tìm thấy trang</h2>
                </div>
            </div>
        </div>
    </div>

    <section class="contact-section">
        <div class="contact-bottom bg-white text-center py-5">
            <div class="container">
                <h1 class="display-4 fw-bold">403</h1>
                <p class="lead fw-bold">Xin lỗi, bạn không có quyền truy cập vào trang này.</p>
                <a href="/" class="btn btn-primary mt-3">Quay lại Trang chủ</a>
            </div>
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
<?php require_once(__DIR__ . '/../client/footer.php'); ?>