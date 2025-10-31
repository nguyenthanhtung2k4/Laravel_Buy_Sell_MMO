<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = '' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
?>
<main>
    <section class="py-5 bg-offWhite">
        <div class="container">
            <div class="rounded-3">

                <div class="row">
                    <div class="col-lg-6 p-3 p-lg-5 m-auto">
                        <div class="login-userset">
                            <div class="login-card">
                                <div class="login-heading">
                                    <h3>Đăng Nhập Tài Khoản</h3>
                                    <p>Điền vào các trường để vào tài khoản của bạn</p>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="text" id="username" class="form-control floating">
                                    <label class="focus-label">Tài khoản</label>
                                </div>
                                <div class="form-wrap form-focus pass-group">
                                    <span class="form-icon">
                                        <i class="toggle-password feather-eye-off"></i>
                                    </span>
                                    <input type="password" id="password" class="pass-input form-control  floating">
                                    <label class="focus-label">Mật khẩu</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <div class="form-wrap">
                                            <label class="custom_check mb-0">Lưu phiên đăng nhập
                                                <input type="checkbox" id="remember" name="remember">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-wrap text-md-end">
                                            <a href="/client/forgot-password" class="forgot-link">Quên mật khẩu?</a>
                                            </div>
                                    </div>
                                </div>
                                <?php if ($TN->site('status_captcha') == 1) : ?>
                                <div class="d-flex justify-content-center mb-2">
                                    <div id="cf-turnstile" class="cf-turnstile" data-sitekey="<?= $TN->site('site_key') ?>"></div>
                                </div>
                                <?php endif ?>
                                <div class="form-wrap mantadory-info d-none">
                                    <p><i class="feather-alert-triangle"></i>Fill all the fields to submit</p>
                                </div>
                                <button type="button" class="btn btn-primary w-100" id="btnLogin">Đăng Nhập</button>
                            <div class="login-or">
                                    <span class="span-or">or sign up with</span>
                                </div>
                                <ul class="login-social-link d-flex justify-content-center">
                                    <li>
                                        <a href="/login/google">
                                            <img src="/assets/images/google-icon.svg" alt="Google"> Google
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="acc-in">
                                <p>Không có tài khoản ?
                                <a href="/client/register"> Tạo tài khoản </a></p>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
$("#btnLogin").on("click", function() {
    $('#btnLogin').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

    $.ajax({
        url: "<?=base_url('ajaxs/client/login.php');?>",
        method: "POST",
        dataType: "JSON",
        data: {
            username: $("#username").val(),
            password: $("#password").val(),
            <?php if ($TN->site('status_captcha') == 1) : ?>
            captcha_response: turnstile.getResponse()
            <?php endif ?>
        },
        success: function(respone) {
            if (respone.status == 'success') {
                showMessage(respone.msg, 'success');
                setTimeout(function() {
                    location.href = '<?=BASE_URL('');?>';
                }, 500);
            } else {
                showMessage(respone.msg, 'error');
            }

            $('#btnLogin').html('Đăng Nhập').prop('disabled', false);
        },
        error: function() {
            showMessage('Không thể xử lý', 'error');
            $('#btnLogin').html('Đăng Nhập').prop('disabled', false);
        }
    });
});
</script>

<?php require_once __DIR__ . '/footer.php';?>