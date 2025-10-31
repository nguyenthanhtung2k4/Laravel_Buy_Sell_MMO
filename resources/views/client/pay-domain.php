<?php
define("IN_SITE", true);
require_once __DIR__ . '/../../../core/is_user.php';
$body['header'] = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '
';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$domain = isset($_GET['domain']) ? xss($_GET['domain']) : '';

if (!$domain) {
    die('Không tìm thấy tên miền! (Biến domain rỗng)');
}

$parts = explode('.', $domain);

// Gộp lại phần mở rộng nếu domain có nhiều dấu chấm (vd: co.uk)
$sub = $parts[0] ?? '';
$ext = implode('.', array_slice($parts, 1)); // dùng phần còn lại làm phần mở rộng

if (!$sub || !$ext) {
    die("Tên miền không hợp lệ! sub: {$sub} | ext: {$ext}");
}

$row = $TN->get_row("SELECT * FROM tbl_list_domain WHERE name = '{$ext}'");

if (!$row) {
    die("Không tìm thấy phần mở rộng tên miền: '{$ext}' trong cơ sở dữ liệu!");
}

$title = 'Thanh Toán Tên Miền - ' . $TN->site('title');
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
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
                            <li class="breadcrumb-item" aria-current="page">Thanh Toán Tên Miền</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Thanh Toán Tên Miền <?=$domain?>
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
                    <h4 class="card-title">Chu kỳ thanh toán</h4>
                    <?php
                    $price = $row['price'];
                    $cycles = [1, 2, 3, 4, 5];
                    ?>
                    <div class="row g-2 mb-3" id="paymentCycles">
                        <?php foreach ($cycles as $month): ?>
                            <div class="col-6 col-md-4">
                                <button class="btn border w-100 payment-cycle"
                                        data-month="<?= $month ?>"
                                        data-price="<?= $price * $month ?>">
                                    <div><?= $month ?> Năm</div>
                                    <div class="text-danger"><?= format_cash($price * $month) ?> đ</div>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên miền của bạn</label>
                        <input type="text" id="domain" class="form-control" value="<?= htmlspecialchars($domain) ?>" readonly>
                    </div>
                    <div class="row">
    <div class="col-md-6">
        <label class="form-label">Nameserver 1 (NS1)</label>
        <input type="text" id="ns1" class="form-control mb-2" placeholder="vd: ns1.example.com" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nameserver 2 (NS2)</label>
        <input type="text" id="ns2" class="form-control mb-2" placeholder="vd: ns2.example.com" required>
    </div>
</div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Thông tin đơn hàng</h3>
                    <div class="bg-light p-3 rounded mb-3">
                        <p id="priceDetails"><?=$domain?> : <?= format_cash($price); ?> đ</p>
                        <p id="monthsDetails">1 Năm: <?= format_cash($price); ?> đ</p>
                        <p>Phí Vat: 0đ</p>
                        <p id="totalAmount" class="fw-bold text-danger">Tổng tiền: <?= format_cash($price); ?> đ</p>
                    </div>
                    <input type="text" id="coupon" class="form-control mb-2" placeholder="Nhập mã giảm giá (nếu có)">
                    <input type="hidden" id="token" value="<?= htmlspecialchars($getUser['token']) ?>">
                    <button onclick="confirmAction(<?= $row['id'] ?>)" class="btn btn-primary w-100">Thanh Toán</button>
                    <a href="/client/reg-domain" class="btn btn-link mt-2 d-block">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const price = <?= $row['price'] ?>;
    let selectedYears = 1;
    let selectedPrice = price;
    const domain = document.getElementById('domain').value;

    document.querySelectorAll('.payment-cycle').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.payment-cycle').forEach(btn => btn.classList.remove('active-select'));
            button.classList.add('active-select');
            selectedYears = button.dataset.month;
            selectedPrice = button.dataset.price;
            updateSummary();
        });
    });

    function updateSummary() {
        const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
        document.getElementById('priceDetails').textContent = `<?=$domain?> : ${formatter.format(price)}`;
        document.getElementById('monthsDetails').textContent = `${selectedYears} Năm: ${formatter.format(selectedPrice)}`;
        document.getElementById('totalAmount').textContent = `Tổng tiền: ${formatter.format(selectedPrice)}`;
    }

    function confirmAction(id) {
        Swal.fire({
            title: 'Xác Nhận Thanh Toán',
            text: 'Bạn có chắc muốn thanh toán tên miền này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                buyDomain(id);
            }
        });
    }

    function buyDomain(id) {
        $.ajax({
            url: '/ajaxs/client/buy-domain.php',
            method: 'POST',
            dataType: 'json',
            data: {
                id: id,
                token: $('#token').val(),
                domain: $('#domain').val(),
                coupon: $('#coupon').val(),
                selectedYears: selectedYears,
                ns1: $('#ns1').val(),
                ns2: $('#ns2').val()
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Thành công!', response.msg, 'success').then(() => {
                        window.location.href = '/client/user-history-domain';
                    });
                } else {
                    Swal.fire('Lỗi', response.msg, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Lỗi', 'Không thể xử lý yêu cầu', 'error');
            }
        });
    }

    // Kích hoạt mặc định chu kỳ đầu tiên
    document.querySelector('.payment-cycle')?.classList.add('active-select');
</script>

<?php require_once(__DIR__ . '/footer.php'); ?>