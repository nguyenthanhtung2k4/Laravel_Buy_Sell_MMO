<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = '' . $TN->site('title');
$body['header'] = '
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
';
$body['footer'] = '

';
require_once __DIR__ . '/header.php';
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
                                    <h3>ĐẶT LẠI MẬT KHẨU</h3>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="password" id="otp" class="form-control floating">
                                    <label class="focus-label">OTP</label>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="password" id="password" class="form-control floating">
                                    <label class="focus-label">Mật khẩu</label>
                                </div>
                                <div class="form-wrap form-focus">
                                    <span class="form-icon">
                                        <i class="feather-mail"></i>
                                    </span>
                                    <input type="password" id="repassword" class="form-control floating">
                                    <label class="focus-label">Xác nhận mật khẩu</label>
                                </div>
                              <div class="d-flex justify-content-center mb-2">
                                    <div id="cf-turnstile" class="cf-turnstile" data-sitekey="<?= $TN->site('site_key') ?>"></div>
                                </div>
                                <button class="btn btn-primary w-100" id="btnReset" type="button">Thay Đổi</button>
                               
                            </div>
                            
                        </div>
                       
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

    <script type="text/javascript">
        $("#btnReset").on("click", function() {
            $('#btnReset').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
                'disabled',
                true);
            $.ajax({
                url: "<?=BASE_URL('ajaxs/client/resetPassword.php');?>",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "resetPassword",
                    otp: $("#otp").val(),
                    password: $("#password").val(),
                    repassword: $("#repassword").val(),
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
                    setTimeout("location.href = '<?=BASE_URL('client/login');?>';", 1000);
                } else {
                    Swal.fire("Thất Bại",
                    respone.msg,
                    "error"
                    );
                }
                    $('#btnReset').html('Thay Đổi').prop('disabled', false);
                },
                error: function() {
                    cuteToast({
                        type: "error",
                        message: 'Không thể xử lý',
                        timer: 5000
                    });
                    $('#btnReset').html('Thay Đổi').prop('disabled', false);
                }

            });
        });
    </script>
<?php require_once __DIR__ . '/footer.php';?>