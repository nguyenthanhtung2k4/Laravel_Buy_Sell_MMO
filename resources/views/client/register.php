<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = '' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
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
                                    <h3>Đăng Ký Tài Khoản</h3>
                                    
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-user"></i>
                                    </span>
                                    <input type="text" class="form-control floating" id="username">
                                    <label class="focus-label">Tài khoản *</label>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="email" class="form-control floating" id="email">
                                    <label class="focus-label">Email</label>
                                </div>
                                <div class="form-wrap form-focus pass-group">
                                    <span class="form-icon">
                                        <i class="toggle-password feather-eye-off"></i>
                                    </span>
                                    <input type="password" class="pass-input form-control  floating" id="password">
                                    <label class="focus-label">Mật khẩu</label>
                                </div>
                                
                                <div class="form-wrap">
                                    <label class="custom_check mb-0">Bằng cách đăng nhập, bạn đồng ý với <a href="/client/terms-condition">Điều khoản sử dụng</a> và <a href="/client/privacy-policy">Chính sách bảo mật của chúng tôi</a>
                                        <input type="checkbox" id="TermsUse">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <?php if ($TN->site('status_captcha') == 1) : ?>
                                <div class="d-flex justify-content-center mb-2">
                                    <div id="cf-turnstile" class="cf-turnstile" data-sitekey="0x4AAAAAAAzg2t4PjMpOVmBP"></div>
                                </div>
                                <?php endif ?>
                                <button type="button" id="btnRegister" class="btn btn-primary w-100">Đăng Ký</button>
                                <div class="login-or">
                                    <span class="span-or">or sign up with</span>
                                </div>
                                <ul class="login-social-link d-flex justify-content-center">
                                    <li>
                                        <a href="/login/google">
                                            <img src="/assets/images/google-icon.svg" alt="Facebook"> Google
                                        </a>
                                    </li>
                                  
                                </ul>
                            </div>
                            <div class="acc-in">
                                <p>Bạn đã có tài khoản? <a href="/client/login">Đăng Nhập</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    $("#btnRegister").on("click", function () {
        $('#btnRegister').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

        let username = $("#username").val().trim();
        let email = $("#email").val().trim();
        let password = $("#password").val();
        let TermsUse = $("input[id='TermsUse']:checked").val();
        let captcha = <?= $TN->site('status_captcha') == 1 ? 'typeof turnstile !== "undefined" ? turnstile.getResponse() : ""' : "''" ?>;

        $.ajax({
            url: "<?= BASE_URL('ajaxs/client/register.php'); ?>",
            method: "POST",
            dataType: "json",
            data: {
                username: username,
                email: email,
                password: password,
                TermsUse: TermsUse,
                captcha_response: captcha
            },
            success: function (respone) {
                if (respone.status === 'success') {
                    showMessage(respone.msg, 'success');
                    setTimeout(function () {
                        window.location.href = '<?= BASE_URL(''); ?>';
                    }, 1500);
                } else {
                    showMessage(respone.msg, 'error');
                    $('#btnRegister').html('Đăng Ký').prop('disabled', false);
                }
            },
            error: function () {
                showMessage("Không thể kết nối tới máy chủ.", 'error');
                $('#btnRegister').html('Đăng Ký').prop('disabled', false);
            }
        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>