<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Cài Đặt Hệ Thống | ' . $TN->site('title');
$body['header'] = '
';
$body['footer'] = '
';
require_once(__DIR__ . '/Header.php');
require_once(__DIR__ . '/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>
<?php
if (isset($_POST['SaveSettings']) && $getUser['level'] == 1) {
    foreach ($_POST as $key => $value) {
        $TN->update("settings", array(
            'value' => $value
        ), " `name` = '$key' ");
    }
    die('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
} ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-gear"></i> Cài đặt</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-2">
                                <nav class="nav nav-tabs flex-column nav-style-5 mb-3" role="tablist">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#cai-dat-chung" aria-selected="false"><i
                                            class="bx bx-cog me-2 align-middle d-inline-block"></i>Cài đặt chung</a>
                                </li>
                               <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#ket-noi" aria-selected="false"><i
                                            class="bx bx-plug me-2 align-middle d-inline-block"></i>Kết nối</a>
                            </nav>
                            </div>
                            <div class="col-xl-10">
                                <div class="tab-content">
                                    <div class="tab-pane text-muted show active" id="cai-dat-chung" role="tabpanel">
                                        <h4>Cài đặt chung</h4>
                                        <form action="" method="POST">
                                            <div class="row push mb-3">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Title</td>
                                                                <td>
                                                                    <input type="text" name="title"
                                                                        value="<?= $TN->site('title') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Description</td>
                                                                <td>
                                                                    <textarea name="description"
                                                                        class="form-control"><?= $TN->site('description') ?></textarea>
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Keywords</td>
                                                                <td>
                                                                    <textarea name="description"
                                                                        class="form-control"><?= $TN->site('keywords') ?></textarea>
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Logo</td>
                                                                <td>
                                                                    <input type="text" name="logo"
                                                                        value="<?= $TN->site('logo') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Favicon</td>
                                                                <td>
                                                                    <input type="text" name="favicon"
                                                                        value="<?= $TN->site('favicon') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Ảnh bìa</td>
                                                                <td>
                                                                    <input type="text" name="anhbia"
                                                                        value="<?= $TN->site('anhbia') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                            <tr>
                                                                <td>Author</td>
                                                                <td>
                                                                    <input type="text" name="author"
                                                                        value="<?= $TN->site('author') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Hotline</td>
                                                                <td>
                                                                    <input type="text" name="hotline"
                                                                        value="<?= $TN->site('hotline') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Email</td>
                                                                <td>
                                                                    <input type="text" name="email"
                                                                        value="<?= $TN->site('email') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                            <tr>
                                                                <td>Thời gian lưu đăng nhập</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input name="session_login" type="text"
                                                                            class="form-control"
                                                                            value="<?= $TN->site('session_login') ?>"
                                                                            required>
                                                                        </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Link facebook</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input name="link_facebook" type="link"
                                                                            class="form-control"
                                                                            value="<?= $TN->site('link_facebook') ?>"
                                                                            required>
                                                                        </td>
                                                                        </tr>
                                            <tr>
                                                                <td>Link zalo</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input name="link_zalo" type="text"
                                                                            class="form-control"
                                                                            value="<?= $TN->site('link_zalo') ?>"
                                                                            required>
                                                                        </td>
                                                                        </tr>
                                            <tr>
                                                                <td>Status thông báo nổi</td>
                                                                <td>
                                                                    <select class="form-control" name="status_noti">
                                                                             <option value="1" <?= $TN->site('status_noti') == '1' ? 'selected' : '' ?>>
                                                            Bật
                                                        </option>
                                                        <option value="0" <?= $TN->site('status_noti') == '0' ? 'selected' : '' ?>>
                                                            Tắt
                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cập nhật phiên bản tự động</td>
                                                                <td>
                                                                    <select class="form-control" name="status_update">
                                                                        <option value="1" <?= $TN->site('status_update') == '1' ? 'selected' : '' ?>>
                                                            ON
                                                        </option>
                                                        <option value="0" <?= $TN->site('status_update') == '0' ? 'selected' : '' ?>>
                                                            OFF
                                                        </option>
                                                                    </select>
                                                                    <small>Hệ thống sẽ tự động cập nhật khi có phiên bản
                                                                        mới nếu bạn chọn ON.</small>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Thông báo nổi ngoài trang chủ</td>
                                                                <td>
                                                                    <textarea id="notification"
                                                                        name="notification"><?= $TN->site('notification') ?></textarea>
                                                                </td>
                                                            </tr>
                                                <tr>
                                                                <td>Nội dung trang chính sách</td>
                                                                <td>
                                                                    <textarea id="privacy_policy"
                                                                        name="privacy_policy"><?= $TN->site('privacy_policy') ?>
</textarea>
                                                                </td>
                                                            </tr>
                                                <tr>
                                                                <td>Điều khoản & Điều kiện</td>
                                                                <td>
                                                                    <textarea id="terms"
                                                                        name="terms"><?= $TN->site('terms') ?></textarea>
                                                                </td>
                                                            </tr>
                                                        <tr>
                                                                <td>Nội dung nạp thẻ cào</td>
                                                                <td>
                                                                    <textarea id="card_notice"
                                                                        name="card_notice"><?= $TN->site('card_notice') ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Script/HTML Header trang khách</td>
                                                                <td>
                                                                    <textarea rows="5" name="javascript_header" id="javascript_header"
                                                                        class="form-control"><?=$TN->site('javascript_header');?>
</textarea>
                                                                    <script>
                                                                    var editor = CodeMirror.fromTextArea(document
                                                                        .getElementById("javascript_header"), {
                                                                            lineNumbers: true, // Hiển thị số dòng
                                                                            mode: "htmlmixed", // Ngôn ngữ lập trình
                                                                            theme: "monokai", // Giao diện (có thể chọn các giao diện khác)
                                                                            matchBrackets: true // Hỗ trợ đánh dấu các cặp ngoặc
                                                                        });
                                                                    </script>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Script/HTML Footer trang khách</td>
                                                                <td>
                                                                    <textarea rows="5" name="javascript_footer" id="javascript_footer"
                                                                        class="form-control"><?=$TN->site('javascript_footer');?></textarea>
                                                                        <script>
                                                                    var editor = CodeMirror.fromTextArea(document
                                                                        .getElementById("javascript_footer"), {
                                                                            lineNumbers: true, // Hiển thị số dòng
                                                                            mode: "htmlmixed", // Ngôn ngữ lập trình
                                                                            theme: "monokai", // Giao diện (có thể chọn các giao diện khác)
                                                                            matchBrackets: true // Hỗ trợ đánh dấu các cặp ngoặc
                                                                        });
                                                                    </script>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <button type="submit" name="SaveSettings"
                                                class="btn btn-primary w-100 mb-3">
                                                <i class="fa fa-fw fa-save me-1"></i> Save                                            </button>
                            </form>
                                    </div>
                                    <div class="tab-pane text-muted" id="ket-noi" role="tabpanel">
                                        <h4>Kết nối</h4>
                                        <form action="" method="POST">
                                            <div class="row push mb-3">
                                                <div class="col-md-6">
                                                    <table class="mb-3 table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="https://zshopclone7.cmsnt.net/assets/img/icon-smtp.png"
                                                                        width="20px"> SMTP                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>SMTP Email</td>
                                                                <td>
                                                                    <input type="text" name="email_smtp"
                                                                        value="<?= $TN->site('email_smtp') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>SMTP Password</td>
                                                                <td>
                                                                    <input type="text" name="pass_email_smtp"
                                                                        value="<?= $TN->site('pass_email_smtp') ?>"
                                                                        class="form-control">
                                                                    <small><a
                                                                            href="https://help.cmsnt.co/huong-dan/huong-dan-cau-hinh-smtp-vao-website-shopclone7/"
                                                                            target="_blank" class="text-primary">Hướng
                                                                            dẫn tích hợp SMTP</a></small>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="mb-3 table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/48px-Google_%22G%22_logo.svg.png"
                                                                        width="20px"> Google Login                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Client ID</td>
                                                                <td>
                                                                    <input type="text" name="google_id"
                                                                        placeholder=""
                                                                        value="<?= $TN->site('google_id') ?>"
                                                                        class="form-control">
                                                                        <small><?=BASE_URL('google-callback');?></small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Client Secret</td>
                                                                <td>
                                                                    <input type="text" name="google_secret"
                                                                        placeholder=""
                                                                        value="<?= $TN->site('google_secret') ?>"
                                                                        class="form-control">
                                                                        <small><?=BASE_URL('google-callback');?></small>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="mb-3 table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="https://zshopclone7.cmsnt.net/assets/img/icon-bot-telegram.avif"
                                                                        width="25px"> Bot Telegram                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Telegram Token</td>
                                                                <td>
                                                                    <input type="text" name="token_telegram"
                                                                        value="<?= $TN->site('token_telegram') ?>"
                                                                        class="form-control">
                                                                    <small><a class="text-primary"
                                                                            href="https://help.cmsnt.co/huong-dan/huong-dan-tich-hop-bot-telegram-vao-shopclone7/"
                                                                            target="_blank">Xem hướng dẫn</a></small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Telegram Chat ID</td>
                                                                <td>
                                                                    <input type="text" name="chat_id_telegram"
                                                                        value="<?= $TN->site('chat_id_telegram') ?>"
                                                                        class="form-control">
                                                                    <small><a class="text-primary"
                                                                            href="https://help.cmsnt.co/huong-dan/huong-dan-tich-hop-bot-telegram-vao-shopclone7/"
                                                                            target="_blank">Xem hướng dẫn</a></small>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-bordered table-striped table-hover mb-3">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Cloudflare_Logo.png/1200px-Cloudflare_Logo.png"
                                                                        width="20px"> reCAPTCHA Cloudflare Login                                                               </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>reCAPTCHA</td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        name="status_captcha">
                                                                        <option value="1" <?= $TN->site('status_captcha') == '1' ? 'selected' : '' ?>>
                                                            Bật
                                                        </option>
                                                        <option value="0" <?= $TN->site('status_captcha') == '0' ? 'selected' : '' ?>>
                                                            Tắt
                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>reCAPTCHA Site Key</td>
                                                                <td>
                                                                    <input type="text" name="site_key"
                                                                        value="<?= $TN->site('site_key') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>reCAPTCHA Secret Key</td>
                                                                <td>
                                                                    <input type="text" name="secret_key"
                                                                        value="<?= $TN->site('secret_key') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-bordered table-striped table-hover mb-3">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="https://trumcardre.vn/assets/storage/images/image_Z8XY4.png"
                                                                        width="20px"> Nạp Thẻ Auto                                                             </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Status Card</td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        name="status_card">
                                                                        <option value="1" <?= $TN->site('status_card') == '1' ? 'selected' : '' ?>>
                                                            Bật
                                                        </option>
                                                        <option value="0" <?= $TN->site('status_card') == '0' ? 'selected' : '' ?>>
                                                            Tắt
                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Partner ID</td>
                                                                <td>
                                                                    <input type="text" name="partner_id"
                                                                        value="<?= $TN->site('partner_id') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Partner Key</td>
                                                                <td>
                                                                    <input type="text" name="partner_key"
                                                                        value="<?= $TN->site('partner_key') ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <button type="submit" name="SaveSettings"
                                                class="btn btn-primary w-100 mb-3">
                                                <i class="fa fa-fw fa-save me-1"></i> Save                                            </button>
                            </form>
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
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?php
require_once(__DIR__ . '/Footer.php');
?>
<script>
CKEDITOR.replace("notification");
CKEDITOR.replace("terms");
CKEDITOR.replace("privacy_policy");
CKEDITOR.replace("card_notice");
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the active tab from Local Storage
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        // Show the saved tab
        $('.nav-tabs a[href="#' + activeTab + '"]').tab('show');
    }

    // Save the selected tab to Local Storage
    $('.nav-tabs a').on('shown.bs.tab', function(e) {
        var selectedTab = $(e.target).attr('href').substr(1);
        localStorage.setItem('activeTab', selectedTab);
    });
});
</script>