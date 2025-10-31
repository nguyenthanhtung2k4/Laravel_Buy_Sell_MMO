<?php
$title = 'Thuê Cronjob - ' . $TN->site('title');
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
                            <li class="breadcrumb-item" aria-current="page">Thuê Cronjob</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Thuê Cronjob
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <br>
    <section>
        <div class="container">
            <div class="rounded-3">

                <section class="space-y-6">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="profile-info-card">
                                <!-- Header -->
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        THÊM LINK CRON
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL Cronjob</label>
                                        <input type="url" class="form-control" id="url" name="url" placeholder="Nhập link cron" required="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="cron_expression" class="form-label">Cron Expression</label>
                                        <input type="number" class="form-control" id="time" name="time" placeholder="Nhập số giây" required="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="server" class="form-label">Chọn Máy Chủ</label>
                                        <select class="form-select shadow-none" id="server" name="server" required="">
                                            <option value="">-- Chọn Máy Chủ --</option>
                                            <?php foreach($TN->get_list("SELECT * FROM `server_cron` WHERE `status` = 'ON'") as $row) { ?>
                                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?> (Tối thiểu <?= $row['limit_second']; ?> giây - <?= format_cash($row['price']); ?>đ/tháng)</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="months" class="form-label">Thời Gian Sử Dụng (Số Tháng)</label>
                                        <select class="form-select shadow-none" id="months" name="months" required="">
                                            <option value="">-- Chọn Thời Gian --</option>
                                            <option value="1">1 Tháng</option>
                                            <option value="3">3 Tháng</option>
                                            <option value="6">6 Tháng</option>
                                            <option value="12">12 Tháng</option>
                                        </select>
                                        <div class="form-text">Chọn số tháng để sử dụng cronjob</div>
                                    </div>
                                    
                                    <input id="token" type="hidden" value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">

                                    <button type="button" class="btn btn-primary" id="btnBuy" onclick="buyCron()">Thêm Cronjob</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <div class="profile-info-card">
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        LƯU Ý
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <h4><span style="color:#e74c3c"><strong>Quy định về việc sử dụng hệ thống CRON:</strong></span></h4>
                                    
                                    <li><strong>- Không kích hoạt Firewall đối với các liên kết CRON</strong>: Việc bật Firewall có thể gây gián đoạn hệ thống và ngăn cản CRON thực hiện nhiệm vụ.</li>
                                    
                                    <li><strong>- Sử dụng hệ thống CRON đúng mục đích</strong>: Chúng tôi có quyền tạm ngưng hoặc hủy vĩnh viễn các liên kết CRON vi phạm, đặc biệt trong các trường hợp lạm dụng gây ảnh hưởng đến hệ thống. Các trường hợp này sẽ không được hoàn tiền.</li>
                                    
                                    <li><strong>- Cung cấp chính xác liên kết CRON</strong>: Đảm bảo nhập đầy đủ và chính xác đường dẫn liên kết, bao gồm tiền tố "https://..."</li>
                                    
                                    <li><strong>- Chúng tôi sẽ không hỗ trợ chỉnh sửa URL cron.</strong>Chỉ có thể <strong>tạm dừng</strong> hoặc <strong>bắt đầu</strong> cron job đã thuê (lưu ý khi mua).</li>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    function isValidURL(url) {
        const pattern = new RegExp('^(https?:\\/\\/)?' +
            '((([a-zA-Z0-9\\-]+\\.)+[a-zA-Z]{2,})|' +
            '((\\d{1,3}\\.){3}\\d{1,3}))' +
            '(\\:\\d+)?(\\/[-a-zA-Z0-9@:%_\\+.~#?&//=]*)?$', 'i');
        return !!pattern.test(url);
    }

    function buyCron() {
        const linkCron = document.getElementById('url').value;
        if (!isValidURL(linkCron)) {
            Swal.fire('Failure!', 'Liên kết CRON không hợp lệ', 'error');
            return;
        }
        Swal.fire({
            title: 'Xác nhận thanh toán',
            text: "Bạn có chắc chắn muốn thanh toán không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Huỷ bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                processBuyCron();
            }
        });
    }

    function processBuyCron() {
        const btnBuy = document.getElementById('btnBuy');
        const btnContent = btnBuy.innerHTML;
        $('#btnBuy').html('<i class="fa fa-spinner fa-spin"></i>')
            .prop('disabled',
                true);
        $.ajax({
            url: "/ajaxs/client/buy-cron.php",
            method: "POST",
            dataType: "JSON",
            data: {
                token: $('#token').val(),
                url: $("#url").val(),
                time: $("#time").val(),
                server: $("#server").val(),
                months: $("#months").val(),
            },
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Successful!',
                        text: response.msg,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Xem đơn hàng',
                        cancelButtonText: 'Thuê cron mới',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/client/user-history-cron';
                        }
                    });
                } else {
                    Swal.fire('Failure!', response.msg, 'error');
                }
                $('#btnBuy').html(btnContent).prop('disabled', false);
            },
            error: function() {
                showMessage('Không thể xử lý', 'error');
                $('#btnBuy').html(
                    btnContent
                ).prop('disabled',
                    false);
            }
        });
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>