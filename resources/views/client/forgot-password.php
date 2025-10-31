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
                                    <h3>QUÊN MẬT KHẨU</h3>
                                    <p>Chúng tôi sẽ gửi liên kết để đặt lại mật khẩu của bạn</p>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="email" id="email" class="form-control floating">
                                    <label class="focus-label">Email</label>
                                </div>
                                <?php if ($TN->site('status_captcha') == 1) : ?>
                               <div class="d-flex justify-content-center mb-2">
                                    <div id="cf-turnstile" class="cf-turnstile" data-sitekey="<?= $TN->site('site_key') ?>"></div>
                                </div>
                                <?php endif ?>
                                <button type="button" class="btn btn-primary w-100" id="btnGetOTP">Xác Minh</button>
                               
                            </div>
                            
                        </div>
                       
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    $("#btnGetOTP").on("click", function() {
        $('#btnGetOTP').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?= BASE_URL('ajaxs/client/resetPassword.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: "forgotPassword",
                email: $("#email").val(),
                <?php if ($TN->site('status_captcha') == 1) : ?>
                captcha_response: turnstile.getResponse()
                <?php endif ?>
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    Swal.fire("Thành Công",
                    respone.msg,
                    "success"
                    );
                    setTimeout("location.href = '<?= BASE_URL('client/reset-password'); ?>';", 1000);
                } else {
                    Swal.fire("Thất Bại",
                    respone.msg,
                    "error"
                    );
                }
                $('#btnGetOTP').html('Xác Minh').prop('disabled', false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#btnGetOTP').html('Xác Minh').prop('disabled', false);
            }
        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>