<?php
$title = 'THAY ĐỔI MẬT KHẨU | ' . $TN->site('title');
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
        <div class="row">
                <div class="col-lg-12">
                    <div class="settings-card">
                        <div class="settings-card-head">
                            <h4>Thay đổi mật khẩu</h4>
                        </div>
                        <div class="settings-card-body">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Mật Khẩu Cũ<span
                                                class="text-lime-300">*</span></label>
                                        <input type="password" class="form-control shadow-none"
                                            id="old_password" name="old_password" required>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="token" value="<?= $getUser['token'] ?>" readonly>
                                <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Mật Khẩu Mới<span
                                                class="text-lime-300">*</span></label>
                                        <input type="password" class="form-control shadow-none"
                                            id="new_password" name="new_password" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Xác Nhận Mật Khẩu<span
                                                class="text-lime-300">*</span></label>
                                        <input type="password" class="form-control shadow-none"
                                            id="confirm_new_password" name="confirm_new_password"
                                            required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="button" id="changePass"
                                        class="btn btn-primary">
                                        Thay Đổi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    $("#changePass").on("click", function() {
        $('#changePass').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?=BASE_URL('ajaxs/client/changePassword.php');?>",
            method: "POST",
            dataType: "JSON",
            data: {
                token: $("#token").val(),
                action: "ChangePassword",
                password: $("#old_password").val(),
                newpassword: $("#new_password").val(),
                renewpassword: $("#confirm_new_password").val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    Swal.fire("Thành Công",
                    respone.msg,
                    "success"
                    );
                    setTimeout("location.href = '<?=BASE_URL('client/user-change-password');?>';", 500);
                } else {
                    Swal.fire("Thất Bại",
                    respone.msg,
                    "error"
                    );
                }
                $('#changePass').html('Thay Đổi').prop('disabled',
                    false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#changePass').html('Thay Đổi').prop('disabled',
                    false);
            }

        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>