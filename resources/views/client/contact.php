<?php
$title = 'Liên Hệ - ' . $TN->site('title');
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
            <div class="row">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Liên hệ</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Liên hệ
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <section class="contact-section">

        <div class="contact-bottom bg-white">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="contact-grid w-100">
                            <div class="contact-content">
                                <div class="contact-icon">
                                    <span>
                                        <img src="/assets/images/contact-mail.svg" alt="Icon">
                                    </span>
                                </div>
                                <div class="contact-details">
                                    <h6>Email Address</h6>
                                    <p><?= $TN->site('email') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="contact-grid w-100">
                            <div class="contact-content">
                                <div class="contact-icon">
                                    <span>
                                        <img src="/assets/images/contact-phone.svg" alt="Icon">
                                    </span>
                                </div>
                                <div class="contact-details">   
                                    <h6>Telegram</h6>
                                    <p><?= $TN->site('telegram') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>