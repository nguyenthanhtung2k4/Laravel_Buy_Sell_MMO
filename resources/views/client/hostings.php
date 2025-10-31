<?php
$title = 'Thanh Toán Hosting - ' . $TN->site('title');
$body['header'] = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
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
<?php
if (isset($_GET['id'])) {
    $id = xss($_GET['id']);
    $row = $TN->get_row("SELECT * FROM `tbl_list_hosting` WHERE `id` = '$id'");

    if (!$row) {
        echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
        exit;
    }
} else {
    echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
    exit;
}
?>

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
                        <li class="breadcrumb-item" aria-current="page">Hosting</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    Đăng ký dịch vụ <?=$row['name']?>
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="container py-5" id="order">
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Sản phẩm đã chọn</h3>
                    <div class="bg-light p-3 rounded mb-3">
                        <p class="fw-bold text-uppercase"><?=$row['name']?></p>
                        <ul class="list-unstyled">
                            <li><span><i class="bx bx-check-double"></i></span>Dung lượng: <?= format_cash($row['dungluong']); ?> MB</li>
                            <li><span><i class="bx bx-check-double"></i></span>Băng Thông: <?=$row['bangthong']?></li>
                            <li><span><i class="bx bx-check-double"></i></span>Miễn Phí Chứng Chỉ SSL</li>
                            <li><span><i class="bx bx-check-double"></i></span>Miền Khác: <?= ($row['mienkhac'] === 'unlimited') ? 'Không giới hạn' : $row['mienkhac'] ?></li>
                            <li><span><i class="bx bx-check-double"></i></span>Miền Bí Danh: <?= ($row['mienbidanh'] === 'unlimited') ? 'Không giới hạn' : $row['mienbidanh'] ?></li>
                            <li><span><i class="bx bx-check-double"></i></span>Giao Diện: <?=$row['cpmod']?></li>
                            <li><span><i class="bx bx-check-double"></i></span>Vị Trị Máy Chủ: Việt Nam</li>  
                        </ul>
                    </div>
                    <h4 class="card-title">Chu kỳ thanh toán</h4>
                    <?php
                    $price = $row['price'];
                    $cycles = [1, 2, 3, 6, 9, 12];
                    ?>
                    <div class="row g-2 mb-3" id="paymentCycles">
                        <?php foreach ($cycles as $month): ?>
                            <div class="col-6 col-md-4">
                                <button class="btn border w-100 payment-cycle"
                                        data-month="<?= $month ?>"
                                        data-price="<?= $price * $month ?>">
                                    <div><?= $month ?> Tháng</div>
                                    <div class="text-danger"><?= format_cash($price * $month) ?> đ</div>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mb-3">
                        <label for="domain" class="form-label fw-bold">Đặt tên miền máy chủ</label>
                        <input type="text" id="domain" class="form-control" placeholder="Nhập tên miền của bạn">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm api-sidebar-menu">
                <div class="card-body">
                    <h3 class="card-title">Thống kê đơn hàng</h3>
                    <div class="bg-light p-3 rounded mb-3">
                        <p id="priceDetails"><?=$row['name']?>: <?= format_cash($row['price']); ?> đ</p>
                        <p id="monthsDetails">1 Tháng: <?= format_cash($row['price']); ?> đ</p>
                        <p id="totalAmount" class="fw-bold text-danger">Tổng tiền thanh toán: <?= format_cash($row['price']); ?> đ</p>
                    </div>
                    <div class="mb-3">
                        <input type="text" id="coupon" class="form-control" placeholder="Nhập mã giảm giá nếu có">
                        <button class="btn btn-danger mt-2 w-100" id="applyCouponBtn">Áp dụng</button>
                    </div>
                    <input id="token" type="hidden" value="<?= isset($getUser['token']) ? htmlspecialchars($getUser['token']) : ''; ?>">
                    <button onclick="confirmAction(<?=$row['id']?>)" class="btn btn-primary w-100">Thanh Toán</button>
                    <a href="/client/hosting" class="btn btn-link d-block mt-2">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const price = <?=$row['price']?>;
    let selectedMonths = 1;
    let selectedPrice = price;
    var id = <?=$row['id']?>;

    // Khi DOM đã sẵn sàng
    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý click vào các nút chu kỳ
        document.querySelectorAll('.payment-cycle').forEach(button => {
            button.addEventListener('click', () => {
                // Bỏ active cũ
                document.querySelectorAll('.payment-cycle').forEach(btn => btn.classList.remove('active-select'));

                // Gán active cho nút đang chọn
                button.classList.add('active-select');

                // Lấy dữ liệu từ data attribute
                selectedMonths = button.dataset.month;
                selectedPrice = button.dataset.price;

                // Cập nhật lại thông tin đơn hàng
                updateOrderSummary();
            });
        });

        // Kích hoạt nút đầu tiên mặc định
        const firstBtn = document.querySelector('.payment-cycle');
        if (firstBtn) {
            firstBtn.classList.add('active-select');
        }
    });

    // Cập nhật thống kê đơn hàng
    function updateOrderSummary() {
        const formatter = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        });

        document.getElementById('priceDetails').textContent = `<?=$row['name']?>: ${formatter.format(selectedPrice)}`;
        document.getElementById('monthsDetails').textContent = `${selectedMonths} Tháng: ${formatter.format(selectedPrice)}`;
        document.getElementById('totalAmount').textContent = `Tổng tiền thanh toán: ${formatter.format(selectedPrice)}`;
    }

    // Gửi xác nhận đơn hàng
    const confirmAction = (id) => {
        const domain = document.getElementById('domain').value;
        if (!domain) {
            alert('Vui lòng nhập tên miền!');
            return;
        }

        Swal.fire({
            title: 'Xác Nhận!',
            text: "Bạn đồng ý thực hiện thanh toán hosting?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then(async (confirm) => {
            if (confirm.isConfirmed) {
                await Item(id);
            }
        });
    };

    // AJAX tạo hosting
    const Item = async (id) => {
        Swal.fire({
            icon: "info",
            title: "Đang khởi tạo hosting!",
            html: "Không được tắt trang này, vui lòng đợi trong giây lát!",
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {},
        });

        $.ajax({
            url: '/ajaxs/client/buy-hosting.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                token: $('#token').val(),
                domain: $('#domain').val(),
                coupon: $('#coupon').val(),
                selectedMonths: selectedMonths
            },
            success: function(result) {
                Swal.close();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tạo hosting thành công!',
                        html: `${result.msg}`,
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Xem lịch sử'
                    }).then((confirm) => {
                        if (confirm.isConfirmed) {
                            window.location.href = '/client/user-history-hosting';
                        }
                    });
                } else {
                    Swal.fire('Thất bại', result.msg, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Thất bại', xhr.responseText, 'error');
            }
        });
    };
</script>

<?php require_once(__DIR__ . '/footer.php'); ?>